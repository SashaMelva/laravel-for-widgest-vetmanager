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


});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api-setting/add', [ApiSettingController::class, 'viewRegisterSettingApi'])
        ->name('add-api-setting');

    Route::post('/api-setting/add/post', [ApiSettingController::class, 'store'])
        ->name('add-api-setting-post');

    Route::resource('api-settings', ApiSettingController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
