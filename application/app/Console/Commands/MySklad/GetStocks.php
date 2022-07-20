<?php

namespace App\Console\Commands\MySklad;

use App\Services\MySklad\Client;
use Illuminate\Console\Command;

class GetStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysklad:stocks';

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
        $apiClient = new Client('8a310ef18f966a55a503b0bddb18e379834fabdb');

        $stocks = $apiClient->service
            ->stocks()
            ->getAllStocks();

        dd($stocks);

        /*
         *   0 => array:13 [
    "meta" => array:5 [
      "href" => "https://online.moysklad.ru/api/remap/1.2/entity/store/1c60fa18-fcd5-11ea-0a80-0334000d1378"
      "metadataHref" => "https://online.moysklad.ru/api/remap/1.2/entity/store/metadata"
      "type" => "store"
      "mediaType" => "application/json"
      "uuidHref" => "https://online.moysklad.ru/app/#warehouse/edit?id=1c60fa18-fcd5-11ea-0a80-0334000d1378"
    ]
    "id" => "1c60fa18-fcd5-11ea-0a80-0334000d1378"
    "accountId" => "bf1cd7a0-f717-11ea-0a80-090f00000027"
    "owner" => array:1 [
      "meta" => array:5 [
        "href" => "https://online.moysklad.ru/api/remap/1.2/entity/employee/bf4cb643-f717-11ea-0a80-013a002eb815"
        "metadataHref" => "https://online.moysklad.ru/api/remap/1.2/entity/employee/metadata"
        "type" => "employee"
        "mediaType" => "application/json"
        "uuidHref" => "https://online.moysklad.ru/app/#employee/edit?id=bf4cb643-f717-11ea-0a80-013a002eb815"
      ]
    ]
    "shared" => false
    "group" => array:1 [
      "meta" => array:4 [
        "href" => "https://online.moysklad.ru/api/remap/1.2/entity/group/bf1d33b0-f717-11ea-0a80-090f00000028"
        "metadataHref" => "https://online.moysklad.ru/api/remap/1.2/entity/group/metadata"
        "type" => "group"
        "mediaType" => "application/json"
      ]
    ]
    "updated" => "2020-09-22 18:30:14.560"
    "name" => "Склад "Яблочная55""
    "externalCode" => "qjLtgihSgwKJhVjYTrQwP3"
    "archived" => false
    "pathName" => ""
    "address" => "Омск"
    "addressFull" => array:1 [
      "addInfo" => "Омск"
    ]
  ]
         */
        return 0;
    }
}
