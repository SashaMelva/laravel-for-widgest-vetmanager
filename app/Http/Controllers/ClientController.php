<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewClientRequest;
use App\Http\Service\DataVetmanagerApi;
use App\Http\Service\VetmanagerApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws VetmanagerApiGatewayException
     */
    public function index()
    {
        $clients = (new DataVetmanagerApi(Auth::user()))->getClientData();
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client/add-client', ['client' => null]);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function show(string $id)
    {
        $viewDataController = new DataVetmanagerApi(Auth::user());

        $client = $viewDataController->getClientById((int)$id);
        $pets = $viewDataController->getPetDataForClient($client);

        return view('client/profile-client', ['pets' => $pets, 'client' => $client, 'clientId' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function edit(string $clientId)
    {
        $viewDataController = new DataVetmanagerApi(Auth::user());
        $client = $viewDataController->getClientById($clientId);

        return view('client/edit-client', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     * @throws GuzzleException
     */
    public function update(StorePostNewClientRequest $request, string $clientId)
    {
        $validate = $request->validated();

        $validateForJsonApi = [
            'last_name' => $validate['lastName'],
            'first_name' => $validate['firstName'],
            'middle_name' => $validate['middleName']
        ];

        (new VetmanagerApi(Auth::user()))->put($clientId, $validateForJsonApi, 'client');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     * @throws GuzzleException
     */
    public function destroy(string $clientId)
    {
        (new VetmanagerApi(Auth::user()))->delete($clientId, 'client');
        return redirect()->route('dashboard');
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     */
    public function search(Request $request)
    {
        $apiController = new DataVetmanagerApi(Auth::user());

        $lastName = trim($request->lastName);
        $firstName = trim($request->firstName);
        $middleName = trim($request->middleName);

        if (!empty($lastName) && !empty($firstName) && !empty($middleName)) {
            $clients = $apiController->searchClientByAllParam($lastName, $firstName, $middleName);
            return view('dashboard', ['clients' => $clients]);
        }

        $clients = [];

        if (!empty($lastName)) {
            $clientsForApi = $apiController->searchClientByValue('last_name', $lastName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }
        if (!empty($firstName)) {
            $clientsForApi = $apiController->searchClientByValue('first_name', $firstName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }
        if (!empty($middleName)) {
            $clientsForApi = $apiController->searchClientByValue('middle_name', $middleName);
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
        if (empty($firstArray)) {
            return $secondArray;
        }

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
