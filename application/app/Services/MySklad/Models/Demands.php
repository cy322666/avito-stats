<?php

namespace App\Services\MySklad\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Demands extends Base
{
    const URL = 'demand';

    protected string $base_url;
    private Base $base;

    public function __construct(Base $base)
    {
        $this->base     = $base;
        $this->base_url = $base->base_url;
    }

    public function all(int $limit, int $offset)
    {
        $array = [];

//        $query = '?limit='.$limit.'&offset='.$offset;
//
//        $response = Http::withHeaders($this->getHeaders())->get($this->base_url.self::URL.$query);
//
//        $meta = $response->json()['meta'];
//
//        $count = intval(ceil($meta['size'] / $limit));
//
//        Log::info(__METHOD__.' > count '.$count);

        for ($i = 0; $i < 5; $i++) {

            Log::info(__METHOD__.' > $i : '.$i.' $offset '.$offset);

            $query = '?limit='.$limit.'&offset='.$offset;

            $response = Http::withHeaders($this->getHeaders())->get($this->base_url.self::URL.$query);

            $offset = $limit + $offset;

            $array = array_merge($array, $response->json()['rows']);
        }

        Log::info(__METHOD__.' > end');

        return [
            'array'  => $array,
            'offset' => $offset,
        ];
    }

    private static function checkSize($size, $current_cursor): bool
    {
        return (bool)$size > $current_cursor;
    }

    protected function getHeaders(): array
    {
        return [
            'application/json',
            'Authorization' => 'Bearer '.$this->base->token,
        ];
    }
}
