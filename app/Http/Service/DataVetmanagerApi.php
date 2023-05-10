<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Otis22\VetmanagerRestApi\Query\Builder;
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\DAO\Breed;
use VetmanagerApiGateway\DO\DTO\DAO\Client;
use VetmanagerApiGateway\DO\DTO\DAO\Pet;
use VetmanagerApiGateway\DO\DTO\DAO\PetType;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class DataVetmanagerApi extends Controller
{
    private ApiGateway $apiGateway;

    /**
     * @throws VetmanagerApiGatewayRequestException
     */
    public function __construct(string $domainName, string $apiKey)
    {
        $this->apiGateway = ApiGateway::fromDomainAndApiKey($domainName, $apiKey, true);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getTypeIdForTitle(string $title): int
    {
        $breeds = PetType::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('title', $title),
            1
        );

        return (int)$breeds[0]->id;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getBreedIdForTitle(string $title): int
    {
        $breeds = Breed::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('title', $title),
            1
        );

        return (int)$breeds[0]->id;
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getClientData(): RedirectResponse|array
    {
        try {
            $clients = Client::getByQueryBuilder($this->apiGateway,
                (new Builder())
                    ->where('status', 'ACTIVE'),
                50
            );

            return $clients;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getClientById(int $clientId): ?Client
    {
        try {
            return Client::getById($this->apiGateway, $clientId);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getPetById(int $petId): ?Pet
    {
        try {
            $pet = Pet::getById($this->apiGateway, $petId);
            return $pet;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function getPetDataForClient(?Client $client): array
    {
        try {
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

            return $clientPets;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClientByAllParam(string $firstName, string $middleName, string $lastName): array
    {
        return Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where('last_name', $lastName)
                ->where('first_name', $firstName)
                ->where('middle_name', $middleName),
            50
        );
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClientByValue(string $property, string $value): array
    {
        return Client::getByQueryBuilder($this->apiGateway,
            (new Builder())
                ->where('status', 'ACTIVE')
                ->where($property, $value),
            50
        );
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
}
