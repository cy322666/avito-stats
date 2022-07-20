<?php

namespace App\Console\Commands\MySklad;

use App\Services\MySklad\Client;
use Illuminate\Console\Command;

class SkuTimeline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysklad:timelines';

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
        $apiClient = new Client('8a310ef18f966a55a503b0bddb18e379834fabdb');

        $skus = $apiClient->service
            ->stocks()
            ->getByMoment(
                '1c60fa18-fcd5-11ea-0a80-0334000d1378',
                '2022-01-01',
            );

        /*
         *   177 => array:13 [
    "meta" => array:5 [ …5]
    "stock" => 1.0
    "inTransit" => 0.0
    "reserve" => 0.0
    "quantity" => 1.0
    "name" => "ГЕРБ"
    "code" => "SUB2133404"
    "price" => 20000.0
    "salePrice" => 20000.0
    "uom" => array:2 [ …2]
    "folder" => array:2 [ …2]
    "externalCode" => "nK5r5iTMjmlgeSgq008MZ0"
    "stockDays" => 274.61
  ]
         */
        return 0;
    }
}
