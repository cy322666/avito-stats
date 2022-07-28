<?php

namespace App\Console\Commands\MySklad;

use App\Models\Account;
use App\Services\MySklad\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info(__METHOD__. ' start ');

        $apiClient = new Client(Account::query()->where('name', 'mc')->first()->token);

        $sales = $apiClient->service
            ->retaildemands()
            ->all();

        foreach ($sales['array'] as $sale) {

            try {

                $agent = explode('/', $sale['agent']['meta']['href']);
                $owner = explode('/', $sale['owner']['meta']['href']);
                $store = explode('/', $sale['store']['meta']['href']);
                $retail = explode('/', $sale['retailStore']['meta']['href']);

                DB::connection('mc')
                    ->table('mc_orders')
                    ->insert([
                        'uuid' => $sale['id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'name'      => $sale['name'],
                        'moment'    => $sale['moment'],
                        'applicable'=> $sale['applicable'],
                        'contragent_uuid' => end($agent),
                        'checkNumber' => $sale['checkNumber'] ?? null,
                        'checkSum'    => $sale['checkSum'] ?? null,
                        'code'    => $sale['externalCode'] ?? null,
                        'created' => $sale['created'] ?? null,
                        'description'    => $sale['description'] ?? null,
                        'documentNumber' => $sale['documentNumber'] ?? null,
                        'fiscal'         => $sale['fiscal'] ?? null,
                        'fiscalPrinterInfo' => $sale['fiscalPrinterInfo'] ?? null,
                        'noCashSum'      => $sale['noCashSum'] ?? null,
                        'employee_uuid'  => end($owner),
                        'payedSum'       => $sale['payedSum'] ?? null,
                        'prepaymentCashSum'   => $sale['prepaymentCashSum'] ?? null,
                        'prepaymentNoCashSum' => $sale['prepaymentNoCashSum'] ?? null,
                        'prepaymentQrSum'     => $sale['prepaymentQrSum'] ?? null,
                        'qrSum'         => $sale['qrSum'] ?? null,
                        'retailStore'   => end($retail),
                        'sessionNumber' => $sale['sessionNumber'] ?? null,
                        'store_uuid'    => end($store),
                        'sum'    => $sale['sum'] ?? null,
                        'vatSum' => $sale['vatSum'] ?? null,
                    ]);

                $saleId = DB::connection('mc')
                    ->table('mc_orders')
                    ->orderByDesc('id')
                    ->first()->id;

                $positions = $apiClient->service
                    ->retaildemands()
                    ->raw($sale['positions']['meta']['href'])['rows'];

                foreach ($positions as $position) {

                    $detail = $apiClient->service
                        ->retaildemands()
                        ->raw($position['assortment']['meta']['href']);

                    DB::connection('mc')
                        ->table('mc_order_positions')
                        ->insert([
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'order_id' => $saleId,
                            'product_uuid' => $detail['id'],
                            'name'      => $detail['name'],
                            'code'      => $detail['code'],
                            'salePrices'=> $detail['salePrices'][0]['value'],
                            'buyPrice'  => $detail['buyPrice']['value'],
                        ]);
                }

            } catch (\Throwable $exception) {

                Log::alert(__METHOD__ . ' : ' . $exception->getMessage());

                continue;
            }
        }

        Log::info(__METHOD__. ' end ');

        return 0;
    }
}
