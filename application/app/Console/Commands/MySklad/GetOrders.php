<?php

namespace App\Console\Commands\MySklad;

use App\Models\Account;
use App\Services\MySklad\Client;
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
    protected $signature = 'mс:orders';

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

        $apiClient = new Client(Account::whereName('mc')->first()->token);

        $sales = $apiClient->service
            ->retaildemands()
            ->all();

        try {

            foreach ($sales['array'] as $sale) {

                $agent = explode('/', $sale['agent']['meta']['href']);
                $owner = explode('/', $sale['owner']['meta']['href']);
                $store = explode('/', $sale['store']['meta']['href']);
                $retail = explode('/', $sale['retailStore']['meta']['href']);

                DB::connection('mc')
                    ->table('mc_orders')
                    ->insert([
                        'name'      => $sale['name'],
                        'moment'    => $sale['moment'],
                        'applicable'=> $sale['applicable'],
                        'contragent_uuid' => end($agent),
                        'checkNumber' => $sale['checkNumber'] ?? null,
                        'checkSum'    => $sale['checkSum'],
                        'code'    => $sale['code'],
                        'created' => $sale['created'],
                        'description'    => $sale['description'],
                        'documentNumber' => $sale['documentNumber'],
                        'fiscal'         => $sale['fiscal'],
                        'fiscalPrinterInfo' => $sale['fiscalPrinterInfo'],
                        'noCashSum'      => $sale['noCashSum'],
                        'employee_uuid'  => end($owner),
                        'payedSum'       => $sale['payedSum'],
                        'prepaymentCashSum'   => $sale['prepaymentCashSum'],
                        'prepaymentNoCashSum' => $sale['prepaymentNoCashSum'],
                        'prepaymentQrSum'     => $sale['prepaymentQrSum'],
                        'qrSum'         => $sale['qrSum'],
                        'retailStore'   => end($retail),
                        'sessionNumber' => $sale['sessionNumber'],
                        'store_uuid'    => end($store),
                        'sum'    => $sale['sum'],
                        'vatSum' => $sale['vatSum'],
                    ]);

                $saleId = DB::connection('mc')
                    ->table('mc_payments')
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
                            'order_id' => $saleId,
                            'product_uuid' => $detail['id'],
                            'name'      => $position['name'],
                            'code'     => $position['code'],
                            'salePrices'=> $position['salePrices']['value'],
                            'buyPrice'  => $position['buyPrice']['value'],
                        ]);
                }
            }
        } catch (\Throwable $exception) {

            Log::alert(__METHOD__ . ' : ' . $exception->getMessage());
        }

        return 0;
    }
}
