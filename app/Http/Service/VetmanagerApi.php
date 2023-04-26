<?php

namespace App\Http\Service;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\WithAuth;

class VetmanagerApi
{
    private Client $client;
    private string $apiKey;
    private string $model;

    public function __construct(User $user, string $model)
    {
        $this->apiKey = $user->userSettingApi()->key;
        $this->client = new Client(['base_uri' => $user->userSettingApi()->url]);
        $this->model = $model;
    }
//    private function getApiSettingUser()
//    {
//        $idUser = 1;
//
//        $settingApi = DB::table('users')->select('users.id', 'api_settings.id', 'api_settings.url', 'api_settings.key')
//            ->join('api_settings', 'users.idApi', '=', 'api_settings.id')
//            ->where('users.id', '=', $idUser)
//            ->limit(1)
//            ->get();
//
//        $this->apiKey = $settingApi->key;
//        $this->apiDomen = $settingApi->url;
//    }

    private function authenticationUserHeaders()
    {
        return new WithAuth(new ByApiKey(new ApiKey($this->apiKey)));
    }

    /**
     * @throws GuzzleException
     */
    public function add(array $validData): void
    {
        $this->client->request(
            'POST',
            $this->uri($this->model)->asString(),
            [
                'headers' => $this->authenticationUserHeaders()->asKeyValue(),
                'json' => $validData
            ]
        );
    }

    public function delete(int $id)
    {
        $this->client->delete(
            uri($this->model)->asString() . "/$id",
            ['headers' => $this->authenticationUserHeaders()->asKeyValue()]
        );
    }

    public function update()
    {

    }
}
