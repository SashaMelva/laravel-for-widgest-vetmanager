<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewPetRequest;
use App\Http\Service\DataVetmanagerApi;
use App\Http\Service\VetmanagerApi;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayRequestException;

class PetController extends Controller
{

    /**
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    private function getVetmanagerApi(): DataVetmanagerApi
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new Exception('Model getting error Users');
        }

        return new DataVetmanagerApi($user->apiSetting->url, $user->apiSetting->key);
    }


    /**
     * Show the form for creating a new resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function create(int $clientId)
    {
        $vetmanagerApi = $this->getVetmanagerApi();

        $breedsAllData = $vetmanagerApi->getAllBreedsPet();
        $typesAllPet = $vetmanagerApi->getAllTypesPet();

        return view('pet/add-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'clientId' => $clientId]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function store(StorePostNewPetRequest $request, int $clientId)
    {
        $validate = $request->validated();

        $vetmanagerApi = $this->getVetmanagerApi();
        $typeId = $vetmanagerApi->getTypeIdForTitle($validate['type-pet']);
        $breedId = $vetmanagerApi->getBreedIdForTitle($validate['breed']);

        $validateForJsonApi = [
            'alias' => $validate['alias'],
            'type_id' => $typeId,
            'breed_id' => $breedId,
            'owner_id' => $clientId
        ];

        (new VetmanagerApi(Auth::user()))->post($validateForJsonApi, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     * @throws VetmanagerApiGatewayRequestException
     * @throws Exception
     */
    public function edit(string $petId)
    {
        $vetmanagerApi = $this->getVetmanagerApi();

        $pet = $vetmanagerApi->getPetById($petId);
        $breedsAllData = $vetmanagerApi->getAllBreedsPet();
        $typesAllPet = $vetmanagerApi->getAllTypesPet();

        return view('pet/edit-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'pet' => $pet]);
    }

    /**
     * Update the specified resource in storage.
     * @throws VetmanagerApiGatewayRequestException
     * @throws VetmanagerApiGatewayException
     */
    public function update(Request $request, string $petId)
    {
        $validate = $request->validated();

        $vetmanagerApi = $this->getVetmanagerApi();

        $pet = $vetmanagerApi->getPetById($petId);

        $typeId = $vetmanagerApi->getTypeIdForTitle($validate['type-pet']);
        $breedId = $vetmanagerApi->getBreedIdForTitle($validate['breed']);

        $validateForJsonApi = [
            'alias' => $validate['alias'],
            'type_id' => $typeId,
            'breed_id' => $breedId,
            'owner_id' => $pet->client->id
        ];


        (new VetmanagerApi(Auth::user()))->put($petId, $validateForJsonApi, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $petId)
    {
        (new VetmanagerApi(Auth::user()))->delete($petId, 'pet');
        return redirect()->route('dashboard');
    }
}
