<?php

use App\Http\Controllers\Ticket\TicketCreateController;
use App\Http\Controllers\Ticket\TicketDeleteController;
use App\Http\Controllers\Ticket\TicketGetController;
use App\Http\Controllers\Ticket\TicketListController;
use App\Http\Controllers\Ticket\TicketSetConcludedController;
use App\Http\Controllers\Ticket\TicketUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::post('ticket/register', TicketCreateController::class)->name('ticket.register');
    Route::delete('ticket/{id}', TicketDeleteController::class)->name('ticket.delete');
    Route::get('ticket/{id}', TicketGetController::class)->name('ticket.get');
    Route::get('ticket', TicketListController::class)->name('ticket.list');
    Route::put('ticket/{id}', TicketUpdateController::class)->name('ticket.update');
    Route::put('ticket/{id}/conclued', TicketSetConcludedController::class)->name('ticket.set.concluded');
});
