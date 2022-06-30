<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Ads;
use App\Services\Avito\ApiClient;
use Avito\RestApi\Storage\FileStorage;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:ads';

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
     * @throws Exception
     */
    public function handle()
    {
        $account = Account::query()->first();

        $apiClient = new ApiClient(
            $account->client_id,
            $account->token,
            new FileStorage(storage_path())
        );

        foreach ($apiClient->adsAll() as $adsCollection) {

            if (count($adsCollection->data->resources) > 0) {

                foreach ($adsCollection->data->resources as $ads) {

                    try {
                        Ads::query()
                            ->create([
                                'account_id' => $account->account_id,
                                'ads_id' => (integer)$ads->id,
                                'price'  => $ads->price,
                                'category_name' => $ads->category->name ?? null,
                                'title'  => $ads->title,
                                'status' => $ads->status,
                                'url'    => $ads->url,
                            ]);

                    } catch (Exception $exception) {

                        Log::alert(__METHOD__.' '.$exception->getMessage());
                    }
                }
            } else
                return 0;
        }
    }
}
