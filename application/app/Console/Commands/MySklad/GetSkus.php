<?php

namespace App\Console\Commands\MySklad;

use App\Services\MySklad\Client;
use Illuminate\Console\Command;

class GetSkus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysklad:skus';

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

        $skus = $apiClient->service
            ->products()
            ->all(
                1000,
                0,
            );

        /*
         *   67 => array:30 [
    "meta" => array:5 [ …5]
    "id" => "02989aaa-3498-11eb-0a80-0637000cd6fa"
    "accountId" => "bf1cd7a0-f717-11ea-0a80-090f00000027"
    "owner" => array:1 [ …1]
    "shared" => true
    "group" => array:1 [ …1]
    "updated" => "2020-12-06 09:39:02.030"
    "name" => "iPhone 7 32GB Silver 353070098455369"
    "description" => "Коробка, оф.гарант 4мес"
    "code" => "990003055739"
    "externalCode" => "xVOvhBwqgpzI9mzxQeeec3"
    "archived" => false
    "pathName" => "Бывшее в употреблении (Б/У)"
    "productFolder" => array:1 [ …1]
    "useParentVat" => true
    "uom" => array:1 [ …1]
    "images" => array:1 [ …1]
    "minPrice" => array:2 [ …2]
    "salePrices" => array:1 [ …1]
    "buyPrice" => array:2 [ …2]
    "barcodes" => array:1 [ …1]
    "supplier" => array:1 [ …1]
    "paymentItemType" => "GOOD"
    "discountProhibited" => false
    "weight" => 0.0
    "volume" => 0.0
    "variantsCount" => 0
    "isSerialTrackable" => false
    "trackingType" => "NOT_TRACKED"
    "files" => array:1 [ …1]
  ]
         */
        dd($skus);

        return 0;
    }
}
