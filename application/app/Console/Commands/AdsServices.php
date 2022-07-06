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
    public function handle(): int
    {
        Log::info(__METHOD__.' > start');

        $today = Carbon::now()->format('Y-m-d');

        $account = Account::query()->where('id', 1)->first();

        if (!Ads::query()
            ->where('account_id', $account->account_id)
            ->where('services_updated_at', '!=', $today)
            ->first()) {

            $account = Account::query()->where('id', 2)->first();

            if (!Ads::query()
                ->where('account_id', $account->account_id)
                ->where('services_updated_at', '!=', $today)
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
            ->where('services_updated_at', '<', $today)
            ->orWhere('services_updated_at', null)
            ->where('account_id', $account->account_id)
            ->limit(100)
            ->pluck('ads_id')
            ->toArray();

        if ($adIds) {

            foreach ($adIds as $adId) {

                $data = $apiClient->adsServices($account->account_id, $adId);
;
                $services = $data->data->vas ?? [];

                if (count($services) > 0) {

                    foreach ($services as $service) {

                        try {

                            if (!\App\Models\AdsServices::query()
                                ->where('ads_id', $adId)
                                ->where('vas_id', '!=', $service->vas_id)
                                ->where('finish_time', '!=', $service->finish_time)
                                ->first()) {

                                $model = \App\Models\AdsServices::query()
                                    ->create([
                                        'ads_id' => $adId,
                                        'finish_time' => $service->finish_time,
                                        'schedule' => json_encode($service->schedule),
                                        'vas_id'   => $service->vas_id,
                                    ]);

                                if ($service->vas_id == 'xl' || $service->vas_id == 'highlight') {

                                    $vas = $apiClient->adsVas($account->account_id, $adId)->data->vas->$adId;
                                } else {

                                    $vas = $apiClient->adsPackages($account->account_id, $adId)->data->packages->$adId;
                                }

                                foreach ($vas as $key => $v) {

                                    if ($key == $service->vas_id) {

                                        $model->price = $v;
                                        $model->save();
                                    }
                                }
                            }
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
