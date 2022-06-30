<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Ads;
use App\Services\Avito\ApiClient;
use Avito\RestApi\Storage\FileStorage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AdsStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:ads-stats';

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
     * @throws \Exception
     */
    public function handle(): int
    {
        Log::info(__METHOD__.' > start');

        $account = Account::query()->first();

        $apiClient = new ApiClient(
            $account->client_id,
            $account->token,
            new FileStorage(storage_path())
        );

        $today = Carbon::now()->format('Y-m-d');

        $adIds = Ads::query()
            ->where('stats_updated_at', '<', $today)
            ->orWhere('stats_updated_at', null)
            ->limit(200)
            ->pluck('ads_id')
            ->toArray();

        if ($adIds) {

            $stats = $apiClient->adsStats(env('API_USER_ID2'), $adIds, [
                'date_from' => Carbon::now()->subDays(269)->format('Y-m-d'),
                'date_to'   => Carbon::now()->format('Y-m-d'),
            ]);

            foreach ($stats->data->result->items as $items) {

                foreach ($items->stats as $details) {

                    \App\Models\AdsStats::query()
                        ->create([
                            'ads_id' => $items->itemId,
                            'date'   => $details->date,
                            'uniq_views'     => $details->uniqViews,
                            'uniq_contacts'  => $details->uniqContacts,
                            'uniq_favorites' => $details->uniqFavorites,
                        ]);
                }
                Ads::query()
                    ->where('ads_id', $items->itemId)
                    ->update([
                        'stats_updated_at' => $today
                    ]);
            }
        }
        Log::info(__METHOD__.' > end');

        return 0;
    }
}
