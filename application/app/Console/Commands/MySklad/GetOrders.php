<?php

namespace App\Console\Commands\MySklad;

use App\Services\MySklad\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysklad:orders';

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

        $statuses = $apiClient->service
            ->orders()
            ->statuses()['states'];

        foreach ($statuses as $status) {

            $orders = $apiClient->service
                ->orders()
                ->all(
                    1000,
                    0,
                    'state=' . $status['meta']['href'],
                );

            if (count($orders['array']) == 0) {

                continue;
            } else {

                foreach ($orders['array'] as $order) {

                    try {
                        Order::query()->create([
                            'href_order' => $order['meta']['href'],
                            'order_id' => $order['id'],
                            'updated' => $order['updated'],
                            'externalCode' => $order['externalCode'],
                            'moment' => $order['moment'],
                            'sum' => $order['sum'],
                            'href' => $order['store']['meta']['href'] ?? null,
                            'value' => $value,
                            'name' => $order['attributes'][0]['name'] ?? null,
                            'created' => $order['created'],
                            'payedSum' => $order['payedSum'],
                            'shippedSum' => $order['shippedSum'],
                            'invoicedSum' => $order['invoicedSum'],
                            'waitSum' => $order['waitSum'],
                            'status' => $status['name'],
                            'href_supply' => $order['supplies'][0]['meta']['href'] ?? null,
                            'delivery_planned_moment' => $order['deliveryPlannedMoment'] ?? null,
                        ]);

                        $positions = $this->client->service
                            ->orders()
                            ->positions($order['id']);

                        foreach ($positions as $position) {

                            OrderPositions::query()->create([
                                'position_id' => $position['id'],
                                'order_id' => $order['id'],
                                'href_order' => $order['meta']['href'],
                                'href_position' => $position['meta']['href'],
                                'quantity' => $position['quantity'],
                                'price' => $position['price'],
                                'discount' => $position['discount'],
                                'shipped' => $position['shipped'],
                                'vat' => $position['vat'],
                                'inTransit' => $position['inTransit'],
                                'vatEnabled' => $position['vatEnabled'],
                                'href_product' => $position['assortment']['meta']['href'] ?? null,
                            ]);
                        }
                    } catch (\Throwable $exception) {

                        Log::alert(__METHOD__ . ' : ' . $exception->getMessage());

                        continue;
                    }
                }
            }
        }

        return 0;
    }
}
