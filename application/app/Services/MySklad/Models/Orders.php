<?php

namespace App\Services\MySklad\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Orders extends Base
{
    const URL = 'purchaseorder';

    protected string $base_url;
    private Base $base;

    public function __construct(Base $base)
    {
        $this->base     = $base;
        $this->base_url = $base->base_url;
    }

    public function all(int $limit, int $offset, ?string $filter = null)
    {
        $array = [];

        $filter = $filter !== null ? '&filter='.$filter : null;

        $query = '?limit='.$limit.'&offset='.$offset;

        $response = Http::withHeaders($this->getHeaders())->get($this->base_url.self::URL.$query.$filter);

        $meta = $response->json()['meta'];

        $count = intval(ceil($meta['size'] / $limit));

        Log::info(__METHOD__.' > count '.$count);

        for ($i = 0; $i < $count; $i++) {

            Log::info(__METHOD__.' > $i : '.$i.' $offset '.$offset);

            $query = '?limit='.$limit.'&offset='.$offset;

            $response = Http::withHeaders($this->getHeaders())->get($this->base_url.self::URL.$query.$filter);

            $offset = $limit + $offset;

            $array = array_merge($array, $response->json()['rows']);
        }

        Log::info(__METHOD__.' > end');

        return [
            'array'  => $array,
            'offset' =>  count($array) < $offset ? $offset - $limit : $offset,
        ];
    }

    public function positions(string $id)
    {
        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/entity/purchaseorder/'.$id.'/positions');

        return $response->json()['rows'];
    }

    public function get(string $id)
    {
        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/entity/purchaseorder/'.$id);

        return $response->json();
    }

    public function statuses()
    {
        return Http::withHeaders($this->getHeaders())->get($this->base_url.'purchaseorder/metadata')->json();
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
