<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewClientRequest;
use App\Http\Service\VetmanagerApi;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class ClientController extends Controller
{

    /**
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function allDataClient()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new Exception('Не получили пользователя');
        }

        $setting = $user->apiSetting;
        $domainName = $setting->url;
        $apiKey = $setting->key;

        $clients = (new ViewDataController($domainName, $apiKey))->getClientData();
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
     * @throws Exception
     */
    public function profile(string $clientId)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new Exception('Не получили пользователя');
        }

        $setting = $user->getApiSetting();
        $domainName = $setting->url;
        $apiKey = $setting->key;
        $viewDataController = new ViewDataController($domainName, $apiKey);

        $client = $viewDataController->getClientById((int)$clientId);
        $pets = $viewDataController->getPetDataForClient($client);

        return view('client/profile-client', ['pets' => $pets, 'client' => $client, 'clientId' => $clientId]);
    }

    public function viewAdd()
    {
        return view('client/add-client', ['client' => null]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewEdit(int $clientId)
    {
        $setting = Auth::user()->getApiSetting();
        $domainName = $setting->domainName;
        $apiKey = $setting->key;
        $viewDataController = new ViewDataController($domainName, $apiKey);
        $client = $viewDataController->getClientById($clientId);

        return view('client/edit-client', ['client' => $client]);
    }
    /**
     * @throws GuzzleException
     */
    public function add(StorePostNewClientRequest $request)
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
    public function edit(StorePostNewClientRequest $request, int $clientId)
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
        $setting = (Auth::user())->getApiSetting();
        $domainName = $setting->domainName;
        $apiKey = $setting->key;
        $apiController = new ViewDataController($domainName, $apiKey);

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
