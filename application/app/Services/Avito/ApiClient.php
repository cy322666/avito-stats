<?php

/*
 * Avito REST API Client
 *
 * Documentation
 * https://developers.avito.ru/api-catalog
 *
 */

declare(strict_types=1);

namespace App\Services\Avito;

use Avito\RestApi\Http\Client;
use Avito\RestApi\Http\ClientInterface;
use Avito\RestApi\Service\AutoloadService;
use Avito\RestApi\Service\ItemService;
use Avito\RestApi\Service\MessengerService;
use Exception;
use Avito\RestApi\Storage\FileStorage;
use Avito\RestApi\Storage\TokenStorageInterface;

class ApiClient
{
    /**
     * @var ClientInterface
     */
    private ClientInterface $httpСlient;

    /**
     * Avito API constructor
     *
     * @param string $clientId
     * @param string $secret
     * @param TokenStorageInterface|null $tokenStorage
     *
     * @throws Exception
     */
    public function __construct(string $clientId, string $secret, TokenStorageInterface $tokenStorage = null)
    {
        $this->httpСlient = new Client($clientId, $secret, $tokenStorage);

        $this->httpСlient->getToken();
    }

    public function adsAll(): \Generator
    {
        for($i = 1 ; ; $i++) {

            yield $this->httpСlient->sendRequest('core/v1/items', 'GET', [
                'per_page' => 100,
                'page'   => $i,
                'status' => 'active, old, blocked, rejected',
            ]);
        }
    }

    public function adsStats(int $userId, array $adsIds, array $dates): \stdClass
    {
        return $this->httpСlient->sendRequest('stats/v1/accounts/'.$userId.'/items', 'POST', [
            'dateFrom' => $dates['date_from'],
            'dateTo'   => $dates['date_to'],
            'itemIds'  => $adsIds,
            'periodGrouping' => $params['grouping'] ?? 'day',
        ]);
    }

    public function adsServices(int $userId, int $adId): \stdClass
    {
        return $this->httpСlient->sendRequest('core/v1/accounts/'.$userId.'/items/'.$adId.'/');
    }

    public function adsCalls(int $userId, array $adsIds, array $dates): \stdClass
    {
        return $this->httpСlient->sendRequest('core/v1/accounts/'.$userId.'/calls/stats/', 'POST', [
            'dateFrom' => $dates['date_from'],
            'dateTo'   => $dates['date_to'],
            'itemIds'  => $adsIds,
        ]);
    }
}
