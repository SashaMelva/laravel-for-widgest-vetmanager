<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use VetmanagerApiGateway\Exception\VetmanagerApiGatewayException;

class PetController extends Controller
{
    /**
     * @throws VetmanagerApiGatewayException
     */
    public function viewAddPet(int $clientId)
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
}
