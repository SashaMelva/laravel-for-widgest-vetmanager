<?php

use App\Http\Controllers\ApiSettingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'api.setting'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])
        ->name('dashboard');

    Route::resource('clients', ClientController::class);

    Route::get('/clients/search', [ClientController::class, 'search'])
        ->name('clients.search');

    Route::get('/api-setting', [ApiSettingController::class, 'viewApiData'])
        ->name('api-setting');

    Route::resource('pets', PetController::class);
//    Route::get('/add-client', [ClientController::class, 'viewAdd'])
//        ->name('add-client');
//
//    Route::post('/add-client/post', [ClientController::class, 'add'])
//        ->name('add-client-post');
//
//    Route::get('/edit-client/{id}', [ClientController::class, 'viewEdit'])
//        ->name('edit-client');
//
//    Route::post('/edit-client/post/{id}', [ClientController::class, 'edit'])
//        ->name('edit-client-post');
//
//    Route::get('/delete-client/{id}', [ClientController::class, 'delete'])
//        ->name('delete-client');
//
//
//    Route::get('/profile-client/{id}', [ClientController::class, 'profile'])
//        ->name('profile-client');
//


    Route::get('/profile-client/add-pet/{id}', [PetController::class, 'viewAdd'])
        ->name('add-pet');

    Route::get('/profile-client/add-pet/post/{id}', [PetController::class, 'add'])
        ->name('add-pet-post');

    Route::get('/profile-client/edit-pet/{id}', [PetController::class, 'viewEdit'])
        ->name('edit-pet');

    Route::get('/profile-client/edit-pet/post/{id}', [PetController::class, 'edit'])
        ->name('edit-pet-post');

    Route::get('/profile-client/delete-pet/{id}', [PetController::class, 'delete'])
        ->name('delete-pet');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api-setting/add', [ApiSettingController::class, 'viewRegisterSettingApi'])
        ->name('add-api-setting');

    Route::post('/api-setting/add/post', [ApiSettingController::class, 'store'])
        ->name('add-api-setting-post');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
