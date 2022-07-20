<?php


namespace App\Services\MySklad\Models;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Base
{
    protected string $base_url = 'https://online.moysklad.ru/api/remap/1.2/';

//    protected string $login;
//    protected string $password;
    protected string $token;

    public function init($token): Base
    {
//        $this->login    = $login;
//        $this->password = $password;
        $this->token    = $token;

        $this->base_url = 'https://online.moysklad.ru/api/remap/1.2/entity/';

        return $this;
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization: Basic '.$this->token,
//            'Authorization' => 'Basic '.$this->login.':'.$this->password,
            'Accept'        => 'application/json',
        ];
    }

    public function __call($name, $arguments)
    {
        $modelName = __NAMESPACE__.'\\'.ucfirst($name);

        return $this->$name = new $modelName($this);
    }
}
