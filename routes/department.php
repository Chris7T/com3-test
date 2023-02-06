<?php

use App\Http\Controllers\Department\DepartmentCreateController;
use App\Http\Controllers\Department\DepartmentDeleteController;
use App\Http\Controllers\Department\DepartmentGetController;
use App\Http\Controllers\Department\DepartmentListController;
use App\Http\Controllers\Department\DepartmentUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::post('department/register', DepartmentCreateController::class)->name('department.register');
    Route::delete('department/{id}', DepartmentDeleteController::class)->name('department.delete');
    Route::get('department/{id}', DepartmentGetController::class)->name('department.get');
    Route::get('department', DepartmentListController::class)->name('department.list');
    Route::put('department/{id}', DepartmentUpdateController::class)->name('department.update');
});
