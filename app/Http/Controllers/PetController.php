<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewPetRequest;
use App\Http\Service\DataVetmanagerApi;
use App\Http\Service\UserApiSettings;
use App\Http\Service\VetmanagerApi;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class PetController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     * @throws Exception
     */
    public function createAdd(int $clientId)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $vetmanagerApi = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key));

        $breedsAllData = $vetmanagerApi->getAllBreedsPet();
        $typesAllPet = $vetmanagerApi->getAllTypesPet();

        return view('pet/add-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'clientId' => $clientId]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     * @throws GuzzleException
     * @throws Exception
     */
    public function store(StorePostNewPetRequest $request, int $clientId)
    {
        $validate = $request->validated();

        $validateForJsonApi = $this->refactorDataForJson($validate);
        $validateForJsonApi['owner_id'] = $clientId;

        $apiSetting = (new UserApiSettings())->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->post($validateForJsonApi, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    public function edit(string $petId)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $vetmanagerApi = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key));

        $pet = $vetmanagerApi->getPetById($petId);
        $breedsAllData = $vetmanagerApi->getAllBreedsPet();
        $typesAllPet = $vetmanagerApi->getAllTypesPet();

        return view('pet/edit-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'pet' => $pet]);
    }

    /**
     * Update the specified resource in storage.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     * @throws GuzzleException
     * @throws Exception
     */
    public function update(StorePostNewPetRequest $request, string $petId)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $vetmanagerApi = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key));

        $validate = $request->validated();

        $validateForJsonApi = $this->refactorDataForJson($validate);
        $pet = $vetmanagerApi->getPetById($petId);
        $validateForJsonApi['owner_id'] = $pet->client->id;

        $apiSetting = (new UserApiSettings())->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->put($petId, $validateForJsonApi, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     * @throws VetmanagerApiGatewayRequestException
     * @throws GuzzleException
     * @throws Exception
     */
    public function destroy(string $petId)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->delete($petId, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    private function refactorDataForJson($validate)
    {
        $apiSetting = (new UserApiSettings())->getApiSetting();
        $vetmanagerApi = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key));

        $typeId = $vetmanagerApi->getTypeIdForTitle($validate['type-pet']);
        $breedId = $vetmanagerApi->getBreedIdForTitle($validate['breed']);

        return [
            'alias' => $validate['alias'],
            'type_id' => $typeId,
            'breed_id' => $breedId
        ];
    }
}
