<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostNewPetRequest;
use App\Http\Service\VetmanagerApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class PetController extends Controller
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewAddPet()
    {
        $viewDataController = new ViewDataController();

        $breedsAllData = $viewDataController->getAllBreedsPet();
        $typesAllPet = $viewDataController->getAllTypesPet();

        return view('pet/add-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet]);
    }

    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewEditPet(int $petId)
    {
        $viewDataController = new ViewDataController();

        $pet = $viewDataController->getPetByIdAndSaveId($petId);
        $breedsAllData = $viewDataController->getAllBreedsPet();
        $typesAllPet = $viewDataController->getAllTypesPet();

        return view('pet/edit-pet', ['breedsAllData' => $breedsAllData, 'typesAllPet' => $typesAllPet, 'pet' => $pet]);
    }

    /**
     * @throws GuzzleException
     */
    public function edit(StorePostNewPetRequest $request, $petId)
    {
        $validate = $request->validated();
        (new VetmanagerApi(Auth::user()))->put($petId ,$validate, 'pet');

        return redirect()->route('dashboard');
    }

    /**
     * @throws GuzzleException
     */
    public function add(StorePostNewPetRequest $request)
    {
        $validate = $request->validated();
        (new VetmanagerApi(Auth::user()))->post($validate, 'pet');

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
