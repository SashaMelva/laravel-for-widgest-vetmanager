<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class ClientController extends Controller
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function allDataClient()
    {
        $clients = (new ApiController())->getClientData();
        return view('dashboard', [
            'clients' => $clients,
            'searchData' => [
                'lastName' => "",
                'firstName' => "",
                'middleName' => ""]
        ]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function profileClient(int $clientId)
    {
        $client = (new ApiController())->getClientById($clientId);
        $pets = (new ApiController())->getPetDataForClient($client);
        return view('client/profile-client', ['pets' => $pets, 'client' => $client]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function editClient(int $clientId)
    {
        $client = (new ApiController())->getClientById($clientId);
        return view('client/add-client', ['client' => $client]);
    }

    public function addClient()
    {
        return view('client/add-client', ['client' => null]);
    }

    public function deletClient(int $clientId)
    {
        $client = (new ApiController())->getClientById($clientId);
        $pets = (new ApiController())->getPetDataForClient($client);
        return view('client/profile-client', ['pets' => $pets, 'client' => $client]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function searchClient(Request $request)
    {
        $apiController = new ApiController();
        $clients = $apiController->getClientData();

        $lastName = trim($request->lastName);
        $firstName = trim($request->firstName);
        $middleName = trim($request->middleName);

        if (!empty($lastName) && !empty($firstName) && !empty($middleName)) {
            $clients = $apiController->searchClientByAllParam($lastName, $firstName, $middleName);
            return view('dashboard', ['clients' => $clients]);
        }
        if (!empty($lastName)) {
            $clientsForApi = $apiController->searchClientByLastName($lastName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }
        if (!empty($firstName)) {
            $clientsForApi = $apiController->searchClientByFirstName($firstName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }
        if (!empty($middleName)) {
            $clientsForApi = $apiController->searchClientByMiddleName($middleName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }

        $searchData = [
            'lastName' => $lastName,
            'firstName' => $firstName,
            'middleName' => $middleName
        ];

        return view('dashboard', ['clients' => $clients, 'searchData' => $searchData]);
    }

    private function saveRepeatedArrayNameForClient(array $firstArray, array $secondArray): array
    {
        $resultArray = [];

        foreach ($firstArray as $firstValue) {
            foreach ($secondArray as $secondValue) {
                if ($firstValue->lastName == $secondValue->lastName && $firstValue->firstName == $secondValue->firstName && $firstValue->middleName == $secondValue->middleName) {
                    $resultArray[] = $firstValue;
                }
            }
        }

        return $resultArray;
    }
}
