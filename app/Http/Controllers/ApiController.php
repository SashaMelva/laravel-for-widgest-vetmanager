<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\DAO\Client;
use VetmanagerApiGateway\DO\DTO\DAO\Pet;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseEmptyException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayResponseException;

class ApiController extends Controller
{
    private ApiGateway $apiGateway;

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    public function __construct(
        string $domainName = 'sashamel',
        string $apiKey = '58160e1141a1abcfb54ecc42266c7d84'
    )
    {
        $this->apiGateway = ApiGateway::fromDomainAndApiKey(
            $domainName,
            $apiKey,
            true,
        );
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getClientData(): array
    {
        $clients = Client::getByPagedQuery(
            $this->apiGateway,
            (new Builder ())
                ->top(50)
        );
        return !empty($clients) ? $clients : [];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    private function getPetDataForClient(int $clientId): array
    {
        $pets = Pet::getAll($this->apiGateway);

        $client = Client::getById($this->apiGateway, $clientId);

        if (empty($pets)) {
            return [];
        }

        $clientPets = [];

        foreach ($pets as $pet) {
            if ($pet->client->firstName == $client->firstName &&
                $pet->client->middleName == $client->middleName &&
                $pet->client->lastName == $client->lastName) {
                $clientPets[] = $pet;
            }
        }

        return !empty($clientPets) ? $clientPets : [];
    }

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
    private function searchClientByFirstName()
    {

    }

    private function searchClientByMiddleName()
    {

    }

    private function searchClientByLastName()
    {

    }

    private function deleteClient(int $clientId)
    {

    }

    private function getProfileClient()
    {

    }
}
