<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\DAO\Client;
use VetmanagerApiGateway\DO\DTO\DAO\Pet;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use Otis22\VetmanagerRestApi\Headers;
use Otis22\VetmanagerRestApi\Headers\Auth\ServiceName;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Illuminate\Support\Facades\Http;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

class ApiController extends Controller
{
    public function authUserForApi(): Headers\WithAuth
    {
        $apiDate = DB::table('api_settings')->paginate(1);

        $authHeaders = new Headers\WithAuth(
                    new Headers\Auth\ByServiceApiKey(
                        new ServiceName($apiDate['url']),
                        new ApiKey($apiDate['key'])
            )
        );

        return $authHeaders;
    }

    /**
     * @throws VetmanagerApiGatewayRequestException
     */


    /**
     * @throws VetmanagerApiGatewayException
     */

    private function addClient(string $firstName, string $middleName, string $lastName): void
    {
        $clientData = array(
            "type_id" => 3,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "status" => "ACTIVE"
        );
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function editClient(int $clientId, string $firstName, string $middleName, string $lastName): void
    {
        $client = Client::getById($this->apiGateway, $clientId);


        $client->firstName = $firstName;
        $client->middleName = $middleName;
        $client->lastName = $lastName;

        $clientData = array(
            "type_id" => 3,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "status" => "ACTIVE"
        );
    }



    private function deleteClient(int $clientId)
    {

    }

}
