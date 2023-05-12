<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewClientRequest;
use App\Http\Service\DataMapperClient;
use App\Http\Service\DataVetmanagerApi;
use App\Http\Service\UserApiSettings;
use App\Http\Service\VetmanagerApi;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        try {
            $apiSetting = (new UserApiSettings())->getApiSetting();
            $clients = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key))->getClientData();

            return view('dashboard', [
                'clients' => $clients,
                'searchData' => [
                    'lastName' => "",
                    'firstName' => "",
                    'middleName' => ""
                ]
            ]);
        } catch (Exception $e) {
            return redirect()->route('api-settings.create');
        }
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
     * @throws Exception
     */
    public function store(StorePostNewClientRequest $request)
    {
        $validate = $request->validated();
        $validateForJsonApi = (new DataMapperClient($validate))->asArray();

        $apiSetting = (new UserApiSettings())->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->post($validateForJsonApi, 'client');

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     * @throws Exception
     */
    public function show(string $id)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $viewDataController = new DataVetmanagerApi($apiSetting->url, $apiSetting->key);

        $client = $viewDataController->getClientById((int)$id);
        $pets = $viewDataController->getPetDataForClient($client);

        return view('client/profile-client', ['pets' => $pets, 'client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     * @throws Exception
     */
    public function edit(string $clientId)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $client = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key))->getClientById($clientId);

        return view('client/edit-client', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     * @throws GuzzleException
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    public function update(StorePostNewClientRequest $request, string $clientId)
    {
        $validate = $request->validated();
        $validateForJsonApi = (new DataMapperClient($validate))->asArray();

        $apiSetting = (new UserApiSettings())->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->put($clientId, $validateForJsonApi, 'client');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     * @throws GuzzleException
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    public function destroy(string $clientId): RedirectResponse
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->delete($clientId, 'client');

        return redirect()->route('dashboard');
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    public function search(Request $request)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $vetmanagerApi = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key));

        $lastName = trim($request->lastName);
        $firstName = trim($request->firstName);
        $middleName = trim($request->middleName);

        if (!empty($lastName) && !empty($firstName) && !empty($middleName)) {
            $clients = $vetmanagerApi->searchClientByAllParam($lastName, $firstName, $middleName);
            return view('dashboard', ['clients' => $clients]);
        }

        $clients = [];

        if (!empty($lastName)) {
            $clientsForApi = $vetmanagerApi->searchClientByValue('last_name', $lastName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }
        if (!empty($firstName)) {
            $clientsForApi = $vetmanagerApi->searchClientByValue('first_name', $firstName);
            $clients = $this->saveRepeatedArrayNameForClient($clients, $clientsForApi);
        }
        if (!empty($middleName)) {
            $clientsForApi = $vetmanagerApi->searchClientByValue('middle_name', $middleName);
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
