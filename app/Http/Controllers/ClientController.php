<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewClientRequest;
use Illuminate\Http\Request;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class ClientController extends Controller
{

    public function allDataClient()
    {
        $clients = (new ViewDataController())->getClientData();
        return view('dashboard', [
            'clients' => $clients,
            'searchData' => [
                'lastName' => "",
                'firstName' => "",
                'middleName' => ""
            ]
        ]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function profile(int $clientId)
    {
        $client = (new ViewDataController())->getClientById($clientId);
        $pets = (new ViewDataController())->getPetDataForClient($client);
        return view('client/profile-client', ['pets' => $pets, 'client' => $client]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function edit(int $clientId)
    {
        $client = (new ViewDataController())->getClientById($clientId);
        return view('client/add-client', ['client' => $client]);
    }

    public function add()
    {
        return view('client/add-client', [ 'client' => null
//            'client' => [
//                'lastName' => "",
//                'firstName' => "",
//                'middleName' => ""
//            ]
        ]);
    }

    public function delet(int $clientId)
    {
        $client = (new ViewDataController())->getClientById($clientId);
        $pets = (new ViewDataController())->getPetDataForClient($client);
        return view('client/profile-client', ['pets' => $pets, 'client' => $client]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function search(Request $request)
    {
        $apiController = new ViewDataController();
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

    public function storeClient(Request $request)
    {
        dd($request);
       // $validated = $request->validated();
        dd($validated);

    }
}
