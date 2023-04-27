<?php

namespace App\Http\Controllers;

use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\DAO\Breed;
use VetmanagerApiGateway\DO\DTO\DAO\Client;
use VetmanagerApiGateway\DO\DTO\DAO\Pet;
use VetmanagerApiGateway\DO\DTO\DAO\PetType;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class ViewDataController extends Controller
{
    private int $clientId;
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

    public function getApiData()
    {
        $api = [
            'domainName' => 'http://sashamel.vetmanager.ru',
            'apiKey' => '58160e1141a1abcfb54ecc42266c7d84'
        ];

        return view('api-setting', ['apiSetting' => $api]);
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
        return $client ?? null;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getPetByIdAndSaveId(int $petId): ?Pet
    {
        $pet = Pet::getById($this->apiGateway, $petId);

        if ((bool)$pet) {
            //$this->clientId = $petId;
            return $pet;
        }

        return null;
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

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClientByMiddleName(string $middleName): array
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('middle_name', $middleName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClientByLastName(string $lastName): array
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('last_name', $lastName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClientByFirstName(string $firstName): array
    {
        $clients = Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('first_name', $firstName),
            50
        );

        return (bool)$clients ? $clients : [];
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getAllTypesPet(): array
    {
        return PetType::getAll($this->apiGateway);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getAllBreedsPet(): array
    {
        return Breed::getAll($this->apiGateway);
    }

    public function deleteClientById(int $clientId)
    {

    }
}
