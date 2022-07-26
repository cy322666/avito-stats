<?php

namespace App\Console\Commands\MySklad;

use App\Models\Account;
use App\Models\MySklad\Skus;
use App\Services\MySklad\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mс:stocks';

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

        $stocks = $apiClient->service
            ->stocks()
            ->getAllStocks();

        try {
            foreach ($stocks as $stock) {

                DB::connection('mc')
                    ->table('mc_stocks')
                    ->insert([
                        'uuid' => $stock['id'],
                        'name' => $stock['name'],
                        'address' => $stock['address'],
                ]);
            }
        } catch (\Exception $exception) {

            Log::alert(__METHOD__.' : '.$exception->getMessage());
        }

        return 0;
    }
}
