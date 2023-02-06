<?php

use App\Http\Controllers\Service\ServiceCreateController;
use App\Http\Controllers\Service\ServiceDeleteController;
use App\Http\Controllers\Service\ServiceGetController;
use App\Http\Controllers\Service\ServiceListController;
use App\Http\Controllers\Service\ServiceUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::post('service/register', ServiceCreateController::class)->name('service.register');
    Route::delete('service/{id}', ServiceDeleteController::class)->name('service.delete');
    Route::get('service/{id}', ServiceGetController::class)->name('service.get');
    Route::get('service', ServiceListController::class)->name('service.list');
    Route::put('service/{id}', ServiceUpdateController::class)->name('service.update');
});
