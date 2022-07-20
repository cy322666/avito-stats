<?php

namespace App\Services\MySklad\Models;

use Illuminate\Support\Facades\Http;

class Stocks extends Base
{
    const URL = '';

    protected string $base_url;
    protected string $token;
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

        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/report/stock/bystore'.$query);

        $meta = $response->json()['meta'];

        $count = intval(ceil($meta['size'] / $limit));

        for ($i = 0; $i < $count; $i++) {

            $query = '?limit='.$limit.'&offset='.$offset;

            $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/report/stock/bystore'.$query);

            $offset = $limit + $offset;

            $array = array_merge($array, $response->json()['rows']);
        }
        return [
            'array'  => $array,
            'offset' => $offset,
        ];
    }

    public function get(string $stock_id, $limit, $offset)
    {
        $array = [];

        $query = '?filter=store=https://online.moysklad.ru/api/remap/1.2/entity/store/'.$stock_id.'&limit='.$limit.'&offset='.$offset;

        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/report/stock/all'.$query);

        $meta = $response->json()['meta'];

        $count = intval(ceil($meta['size'] / $limit));

        for ($i = 0; $i < $count; $i++) {

            $query = '?filter=store=https://online.moysklad.ru/api/remap/1.2/entity/store/'.$stock_id.'&limit='.$limit.'&offset='.$offset;

            $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/report/stock/all'.$query);

            $offset = $limit + $offset;

            $array = array_merge($array, $response->json()['rows']);
        }
        return [
            'array'  => $array,
            'offset' => $offset,
        ];
    }

    public function warehouseStocks(string $href, string $moment)
    {
        return Http::withHeaders($this->getHeaders())
            ->get('https://online.moysklad.ru/api/remap/1.2/report/stock/bystore?filter=variant='.$href.'&filter=moment='.$moment)->json();
    }

    public function getOne($hrefProduct, $moment)
    {
        $query = '?filter=variant='.$hrefProduct.'&filter=moment='.$moment;

        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/report/stock/all'.$query);

        return $response->json()['rows'][0] ?? [];
    }

    public function getByMoment(string $storeId, string $moment)
    {
        $query = '?filter=store=https://online.moysklad.ru/api/remap/1.2/entity/store/'.$storeId.';moment='.urlencode($moment).'&limit=1000&offset=0';

        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/report/stock/all'.$query);

        return $response->json()['rows'];
    }

    public function getAllStocks()
    {
        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/entity/store');

        return $response->json()['rows'];
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
