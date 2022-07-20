<?php

namespace App\Services\MySklad\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Positions extends Base
{
    const URL = 'positions';

    protected string $base_url;
    private Base $base;

    public function __construct(Base $base)
    {
        $this->base     = $base;
        $this->base_url = $base->base_url;
    }

    public function get($id)
    {
        $response = Http::withHeaders($this->getHeaders())->get('https://online.moysklad.ru/api/remap/1.2/entity/supply/'.$id.'/positions');

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
