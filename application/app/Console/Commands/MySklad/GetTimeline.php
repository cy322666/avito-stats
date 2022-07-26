<?php

namespace App\Console\Commands\MySklad;

use App\Models\Account;
use App\Services\MySklad\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetTimeline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mс:timelines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Остатки по дням';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info(__METHOD__. ' start ');

        $apiClient = new Client(Account::whereName('mc')->first()->token);

        foreach (DB::connection('mc')
                     ->table('mc_stocks')
                     ->get(['uuid'])->all() as $stockId) {

            $lastDate = DB::connection('mc')
                ->table('mc_timelines')
                ->where('stock_id', $stockId->uuid)
                ->orderByDesc('date')
                ->first();

            $lastDate = $lastDate->date ?? '2022-01-01';

            for (;;) {

                if (Carbon::parse($lastDate)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {

                    continue 2;
                } else
                    $lastDate = Carbon::parse($lastDate)->addDay()->format('Y-m-d');

                $skus = $apiClient->service
                    ->stocks()
                    ->getByMoment($stockId->uuid, $lastDate.' 13:00:00');

                foreach ($skus as $sku) {

                    DB::connection('mc')
                        ->table('mc_timelines')
                        ->insert([
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'name'     => $sku['name'],
                            'stock_id' => $stockId->uuid,
                            'price'    => $sku['price'],
                            'quantity' => $sku['quantity'],
                            'reserve'  => $sku['reserve'],
                            'stock'    => $sku['stock'],
                            'salePrice'=> $sku['salePrice'],
                            'inTransit'=> $sku['inTransit'],
                            'stockDays'=> $sku['stockDays'],
                            'article'  => $sku['article'] ?? null,
                            'code'     => $sku['code'],
                            'date'     => $lastDate,
                        ]);
                }
            }
        }
        Log::info(__METHOD__. ' stop ');

        return 0;
    }
}
