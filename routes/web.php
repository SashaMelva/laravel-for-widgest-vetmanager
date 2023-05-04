<?php

use App\Http\Controllers\ApiSettingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', [ClientController::class, 'allDataClient'])->middleware(['auth', 'verified', 'api.setting'])->name('dashboard');

Route::get('/api-setting', [ApiSettingController::class, 'viewApiData'])->middleware(['auth', 'verified'])->name('api-setting');

Route::get('/add-client', [ClientController::class, 'viewAdd'])->middleware(['auth', 'verified'])->name('add-client');

Route::post('/add-client/post', [ClientController::class, 'add'])->middleware(['auth', 'verified'])->name('add-client-post');

Route::get('/edit-client/{id}', [ClientController::class, 'viewEdit'])->middleware(['auth', 'verified'])->name('edit-client');

Route::post('/edit-client/post/{id}', [ClientController::class, 'edit'])->middleware(['auth', 'verified'])->name('edit-client-post');

Route::get('/delete-client/{id}', [ClientController::class, 'delete'])->middleware(['auth', 'verified'])->name('delete-client');


Route::get('/profile-client/{id}', [ClientController::class, 'profile'])->middleware(['auth', 'verified'])->name('profile-client');

Route::get('/search-client', [ClientController::class, 'search'])->middleware(['auth', 'verified'])->name('search-client');


Route::get('/profile-client/add-pet/{id}', [PetController::class, 'viewAdd'])->middleware(['auth', 'verified'])->name('add-pet');

Route::get('/profile-client/add-pet/post/{id}', [PetController::class, 'add'])->middleware(['auth', 'verified'])->name('add-pet-post');

Route::get('/profile-client/edit-pet/{id}', [PetController::class, 'viewEdit'])->middleware(['auth', 'verified'])->name('edit-pet');

Route::get('/profile-client/edit-pet/post/{id}', [PetController::class, 'edit'])->middleware(['auth', 'verified'])->name('edit-pet-post');

Route::get('/profile-client/delete-pet/{id}', [PetController::class, 'delete'])->middleware(['auth', 'verified'])->name('delete-pet');


Route::get('/api-setting/add', [ApiSettingController::class, 'viewRegisterSettingApi'])->middleware(['auth', 'verified'])->name('add-api-setting');

Route::post('/api-setting/add/post', [ApiSettingController::class, 'store'])->middleware(['auth', 'verified'])->name('add-api-setting-post');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
