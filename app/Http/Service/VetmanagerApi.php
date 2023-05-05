<?php

namespace App\Http\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\WithAuth;

class VetmanagerApi
{
    private Client $client;
    private string $apiKey;

    public function __construct(string $domainName, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client(['base_uri' => 'https://' . $domainName . '.vetmanager2.ru']);
    }

    private function authenticationUserHeaders(): WithAuth
    {
        return new WithAuth(new ByApiKey(new ApiKey($this->apiKey)));
    }

    /**
     * @throws GuzzleException
     */
    public function post(array $validData, string $model): void
    {
        $response = $this->client->post(
            $this->uri($model),
            [
                'headers' => $this->authenticationUserHeaders()->asKeyValue(),
                'json' => $validData
            ]
        );

        $response->getBody();
    }

    /**
     * @throws GuzzleException
     */
    public function delete(int $id, string $model): void
    {
        $this->client->delete(
            $this->uri($model) . "/$id",
            ['headers' => $this->authenticationUserHeaders()->asKeyValue()]
        );
    }

    /**cr
     * @throws GuzzleException
     */
    public function put(int $id, array $validData, string $model): void
    {
        $this->client->put(
            $this->uri($model) . "/$id",
            [
                'headers' => $this->authenticationUserHeaders()->asKeyValue(),
                'json' => $validData
            ]
        );
    }

    private function uri(string $model): string
    {
        return '/rest/api/' . $model;
    }
}
