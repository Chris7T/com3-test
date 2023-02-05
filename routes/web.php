<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::post('/register', [RegisterController::class, 'register'])->name('user.register');
    Route::post('/login', [LoginController::class, 'login'])->name('user.login');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/logout', [LogoutController::class, 'perform'])->name('user.logout');
        Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')
            ->middleware(['signed']);
        Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

        Route::group(['middleware' => ['verified']], function () {

            //ROTAS VERIFICADAS
        });
    });
});
