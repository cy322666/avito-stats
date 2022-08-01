<?php

namespace App\Console\Commands\MySklad;

use App\Models\Account;
use App\Models\MySklad\Skus;
use App\Services\MySklad\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetVariants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mс:skus-variants';

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

        $skus = $apiClient->service
            ->variants()
            ->all(
                1000,
                Cache::get('mc_variants_offset') ?? 0,
            );

        if (count($skus['array']) > 0) {

            foreach ($skus['array'] as $sku) {

                try {
                    DB::connection('mc')
                        ->table((new Skus)->getTable())
                        ->insert([
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'uuid' => $sku['id'],
                            'name' => $sku['name'],
                            'group'=> $sku['pathName'] ?? null,
                            'code' => $sku['code'],
                            'archived' => $sku['archived'],
                            'article'  => $sku['article'] ?? null,
                            'type' => 'Модификация',
                        ]);

                } catch (\Throwable $exception) {

                    Log::alert($exception->getMessage());
                }
            }
        }
        Cache::put('mc_variants_offset', $skus['offset']);

        Log::info(__METHOD__. ' stop ');

        return 0;
    }
}
