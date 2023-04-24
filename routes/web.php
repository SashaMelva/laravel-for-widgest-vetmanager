<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClientController;
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
});

Route::get('/dashboard', [ClientController::class, 'allDataClient'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api-setting', [ApiController::class, 'getApiData'])->middleware(['auth', 'verified'])->name('api-setting');

Route::get('/add-client', [ClientController::class, 'addClient'])->middleware(['auth', 'verified'])->name('add-client');

Route::get('/edit-client/{id}', [ClientController::class, 'editClient'])->middleware(['auth', 'verified'])->name('edit-client');

Route::get('/profile-client/{id}', [ClientController::class, 'profileClient'])->middleware(['auth', 'verified'])->name('profile-client');

Route::get('/search-client', [ClientController::class, 'searchClient'])->middleware(['auth', 'verified'])->name('search-client');


Route::get('/profile-client/add-pet', function () {
    return view('pet/add-pet/');
})->middleware(['auth', 'verified'])->name('add-pet');

Route::get('/profile-client/edit-pet', function () {
    return view('pet/edit-pet/');
})->middleware(['auth', 'verified'])->name('edit-pet');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
