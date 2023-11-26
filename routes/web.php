<?php

use App\Http\Controllers\Person\PersonCreateController;
use App\Http\Controllers\Person\PersonDestroyController;
use App\Http\Controllers\Person\PersonEditController;
use App\Http\Controllers\Person\PersonIndexController;
use App\Http\Controllers\Person\PersonShowController;
use App\Http\Controllers\Person\PersonStoreController;
use App\Http\Controllers\Person\PersonUpdateController;
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

Route::prefix('persons')->name('persons.')->group(function () {
    Route::get('/create', PersonCreateController::class)->name('create');
    Route::post('/', PersonStoreController::class)->name('store');
    Route::get('/{person}', PersonShowController::class)->name('show');
    Route::get('/{person}/edit', PersonEditController::class)->name('edit');
    Route::put('/{person}', PersonUpdateController::class)->name('update');
    Route::delete('/{person}', PersonDestroyController::class)->name('destroy');
});

Route::get('/', PersonIndexController::class)->name('persons.index');
