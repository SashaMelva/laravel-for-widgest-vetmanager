<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewClientRequest;
use App\Http\Service\VetmanagerApi;
use GuzzleHttp\Exception\GuzzleException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $viewDataController = new ViewDataController();

        $client = $viewDataController->getClientById($clientId);
        $pets = $viewDataController->getPetDataForClient($client);

        return view('client/profile-client', ['pets' => $pets, 'client' => $client, 'clientId' => $clientId]);
    }



    /**
     * @throws VetmanagerApiGatewayException
     */
    public function edit(int $clientId)
    {
        $client = (new ViewDataController())->getClientById($clientId);
        return view('client/add-client', ['client' => $client]);
    }


    public function viewAdd()
    {
        return view('client/add-client', ['client' => null]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewEdit(int $clientId) {
        $viewDataController = new ViewDataController();
        $client = $viewDataController->getClientById($clientId);

        return view('client/add-client', ['client' => $client]);
    }
    /**
     * @throws GuzzleException
     */
    public function store(StorePostNewClientRequest $request)
    {
        $validate = $request->validated();
        $validateForJsonApi = [
            'last_name' => $validate['lastName'],
            'first_name' => $validate['firstName'],
            'middle_name' => $validate['middleName']
        ];

        (new VetmanagerApi(Auth::user()))->post($validateForJsonApi, 'client');

         return redirect()->route('dashboard');
    }

    /**
     * @throws GuzzleException
     */
    public function storeEdit(StorePostNewClientRequest $request)
    {
        $validate = $request->validated();
        (new VetmanagerApi(Auth::user()))->put($validate, 'client');

        return redirect()->route('dashboard');
    }

    /**
     * @throws GuzzleException
     */
    public function delete(int $clientId)
    {
        (new VetmanagerApi(Auth::user()))->delete($clientId, 'client');
        return redirect()->route('dashboard');
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function search(Request $request)
    {
        $apiController = new ViewDataController();

        $lastName = trim($request->lastName);
        $firstName = trim($request->firstName);
        $middleName = trim($request->middleName);

        if (!empty($lastName) && !empty($firstName) && !empty($middleName)) {
            $clients = $apiController->searchClientByAllParam($lastName, $firstName, $middleName);
            return view('dashboard', ['clients' => $clients]);
        }

        $clients = $apiController->getClientData();

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
