<?php

namespace App\Services\MySklad;

use App\Services\MySklad\Models\Base;

class Client
{
    public Base $service;

    public function __construct(string $token)
    {
        $this->service = (new Base())->init($token);

        return $this;
    }
}
