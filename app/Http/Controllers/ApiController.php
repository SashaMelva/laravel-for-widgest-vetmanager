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
    public function getClientData(): array
    {

        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE'),
            50
        );
        return !empty($clients) ? $clients : [];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getClientById(int $clientId): ?Client
    {
        $client = Client::getById($this->apiGateway, $clientId);
        return (bool)$client ? $client : null;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getPetDataForClient(?Client $client): array
    {
        if (is_null($client)) {
            return [];
        }

        $pets = Pet::getAll($this->apiGateway);

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

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClientByAllParam(string $firstName, string $middleName, string $lastName): array
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('last_name', $lastName)
                ->where('first_name', $firstName)
                ->where('middle_name', $middleName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    public function searchClientByMiddleName(string $middleName)
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('middle_name', $middleName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    public function searchClientByLastName(string $lastName)
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('last_name', $lastName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    public function searchClientByFirstName(string $firstName)
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('first_name', $firstName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    private function deleteClient(int $clientId)
    {

    }

    private function getProfileClient()
    {

    }
}
