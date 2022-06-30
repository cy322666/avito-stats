<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Ads;
use App\Services\Avito\ApiClient;
use Avito\RestApi\Storage\FileStorage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AdsServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:ads-services';

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

        $account = Account::query()->first();

        $apiClient = new ApiClient(
            $account->client_id,
            $account->token,
            new FileStorage(storage_path())
        );

        $today = Carbon::now()->format('Y-m-d');

        $adIds = Ads::query()
            ->where('services_updated_at', '<', $today)
            ->orWhere('services_updated_at', null)
            ->limit(300)
            ->pluck('ads_id')
            ->toArray();

        if ($adIds) {

            foreach ($adIds as $adId) {

                $data = $apiClient->adsServices($account->account_id, $adId);
;
                $services = $data->data->vas;

                if (count($services) > 0) {

                    foreach ($services as $service) {

                        try {
                            \App\Models\AdsServices::query()
                                ->updateOrCreate([
                                    'ads_id' => $adId,
                                ],[
                                    'finish_time' => $service->finish_time,
                                    'schedule' => json_encode($service->schedule),
                                    'vas_id'   => $service->vas_id,
                                ]);

                        } catch (\Exception $exception) {

                            Log::alert(__METHOD__.' '.$exception->getMessage());
                        }
                    }
                }
                Ads::query()
                    ->where('ads_id', $adId)
                    ->update([
                        'services_updated_at' => $today
                    ]);
            }
        }
        Log::info(__METHOD__.' > end');

        return 0;
    }
}
