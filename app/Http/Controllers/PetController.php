<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewPetRequest;
use App\Http\Service\DataVetmanagerApi;
use App\Http\Service\VetmanagerApi;
use App\Models\ApiSetting;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class PetController extends Controller
{
    /**
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    private function getApiSetting(): ApiSetting
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new Exception('Model getting error Users');
        }

        return $user->apiSetting;
    }


    /**
     * Show the form for creating a new resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function createAdd(int $clientId)
    {
        $apiSetting = $this->getApiSetting();
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
     */
    public function store(StorePostNewPetRequest $request, int $clientId)
    {
        $validate = $request->validated();

        $validateForJsonApi = $this->refactorDataForJson($validate);
        $validateForJsonApi['owner_id'] = $clientId;

        $apiSetting = $this->getApiSetting();
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
        $apiSetting = $this->getApiSetting();
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
     */
    public function update(StorePostNewPetRequest $request, string $petId)
    {
        $apiSetting = $this->getApiSetting();
        $vetmanagerApi = (new DataVetmanagerApi($apiSetting->url, $apiSetting->key));

        $validate = $request->validated();

        $validateForJsonApi = $this->refactorDataForJson($validate);
        $pet = $vetmanagerApi->getPetById($petId);
        $validateForJsonApi['owner_id'] = $pet->client->id;

        $apiSetting = $this->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->put($petId, $validateForJsonApi, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     * @throws VetmanagerApiGatewayRequestException
     * @throws GuzzleException
     */
    public function destroy(string $petId)
    {
        $apiSetting = $this->getApiSetting();
        (new VetmanagerApi($apiSetting->url, $apiSetting->key))->delete($petId, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * @throws VetmanagerApiGatewayException
     * @throws VetmanagerApiGatewayRequestException
     */
    private function refactorDataForJson($validate)
    {
        $apiSetting = $this->getApiSetting();
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
