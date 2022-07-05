<?php

namespace App\Services\Sipout;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    public function __construct(private string $token) {}

    /**
     * @throws GuzzleException
     */
    public function calls(array $dates)
    {
        $response = (new \GuzzleHttp\Client())
            ->request('GET', 'https://lk.sipout.net/userapi/', [
                'headers' => [
                    'content-type' => 'application/json'
                ],
                'query' => [
                    'key' => $this->token,
                    'method' => 'call_stat',
                    'action' => 'get_list',
                    'ds' => $dates['date_from'],//01.03.2022
                    'de' => $dates['date_to'],
                ],
        ]);

        return json_decode($response->getBody()->getContents())->data->list;
    }
}
