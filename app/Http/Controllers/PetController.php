<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewPetRequest;
use App\Http\Service\VetmanagerApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class PetController extends Controller
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewAdd(int $clientId)
    {
        $viewDataController = new ViewDataController(Auth::user());

        $breedsAllData = $viewDataController->getAllBreedsPet();
        $typesAllPet = $viewDataController->getAllTypesPet();

        return view('pet/add-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'clientId' => $clientId]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewEdit(int $petId)
    {
        $viewDataController = new ViewDataController(Auth::user());

        $pet = $viewDataController->getPetById($petId);
        $breedsAllData = $viewDataController->getAllBreedsPet();
        $typesAllPet = $viewDataController->getAllTypesPet();

        return view('pet/edit-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'pet' => $pet]);
    }

    /**
     * @throws GuzzleException
     * @throws VetmanagerApiGatewayException
     */
    public function add(StorePostNewPetRequest $request, int $clientId)
    {
        $validate = $request->validated();

        $viewDataController = new ViewDataController(Auth::user());
        $typeId = $viewDataController->getTypeIdForTitle($validate['type-pet']);
        $breedId = $viewDataController->getBreedIdForTitle($validate['breed']);

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
     * @throws GuzzleException
     * @throws VetmanagerApiGatewayException
     */
    public function edit(StorePostNewPetRequest $request, $petId)
    {
        $validate = $request->validated();
        $viewDataController = new ViewDataController(Auth::user());

        $pet = $viewDataController->getPetById($petId);

        $typeId = $viewDataController->getTypeIdForTitle($validate['type-pet']);
        $breedId = $viewDataController->getBreedIdForTitle($validate['breed']);

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
     * @throws GuzzleException
     */
    public function delete(int $petId)
    {
        (new VetmanagerApi(Auth::user()))->delete($petId, 'pet');
        return redirect()->route('dashboard');
    }
}
