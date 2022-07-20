<?php

namespace App\Services\MySklad\Models;

use Illuminate\Support\Facades\Http;

class Shipments extends Base
{
    const URL = 'counterparty';

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

        $query = '?limit='.$limit.'&offset='.$offset;

        $response = Http::withHeaders($this->getHeaders())->get($this->base_url.self::URL.$query);
dd($response['rows']);
        $meta = $response->json()['meta'];

        $count = intval(ceil($meta['size'] / $limit));

        for ($i = 0; $i < $count; $i++) {

            $query = '?limit='.$limit.'&offset='.$offset;

            $response = Http::withHeaders($this->getHeaders())->get($this->base_url.self::URL.$query);

            $offset = $limit + $offset;

            $array = array_merge($array, $response->json()['rows']);
        }

        return $array;
    }

    private static function checkSize($size, $current_cursor): bool
    {
        return (bool)$size > $current_cursor;
    }

    protected function getHeaders(): array
    {
        return [
            'application/json',
        ];
    }
}
