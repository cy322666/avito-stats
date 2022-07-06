<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Ads;
use App\Services\Avito\ApiClient;
use Avito\RestApi\Storage\FileStorage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AdsCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:ads-calls';

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
    public function handle()
    {
        Log::info(__METHOD__.' > start');

        if (!Ads::query()
            ->where('account_id', $account->account_id)
            ->where('calls_updated_at', '!=', $today)
            ->first()) {

            $account = Ads::query()->find(2);

            if (!Ads::query()
                ->where('account_id', $account->account_id)
                ->where('calls_updated_at', '!=', $today)
                ->first()) {

                Log::info(__METHOD__.' > end > no account');

                return 0;
            }
        }

        $apiClient = new ApiClient(
            $account->client_id,
            $account->token,
            new FileStorage(storage_path('avito/'))
        );

        $adIds = Ads::query()
            ->where('calls_updated_at', '!=', $today)
            ->orWhere('calls_updated_at', null)
            ->where('account_id', $account->account_id)
            ->limit(100)
            ->pluck('ads_id')
            ->toArray();

        if ($adIds) {

            for ($i = 0; $i < count($adIds); $i++) {

                $items = $apiClient->adsCalls($account->account_id, [$adIds[$i]], [
                    'date_from' => Carbon::now()->subDays(180)->format('Y-m-d'),
                    'date_to'   => Carbon::now()->format('Y-m-d'),
                ])->data->result->items[0];

                if (!empty($items->days)) {

                    foreach ($items->days as $details) {

                        \App\Models\AdsCalls::query()
                            ->create([
                                'ads_id' => $items->itemId,
                                'date'   => $details->date,
                                "answered" => $details->answered,
                                "calls" => $details->calls,
                                "new"   => $details->new,
                                "new_answered" => $details->newAnswered,
                            ]);
                    }
                }
                Ads::query()
                    ->where('ads_id', $items->itemId)
                    ->update([
                        'calls_updated_at' => $today
                    ]);
            }
        }
        Log::info(__METHOD__.' > end');

        return 0;
    }
}
