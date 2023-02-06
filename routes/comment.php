<?php

use App\Http\Controllers\Comment\CommentCreateController;
use App\Http\Controllers\Comment\CommentDeleteController;
use App\Http\Controllers\Comment\CommentGetController;
use App\Http\Controllers\Comment\CommentListController;
use App\Http\Controllers\Comment\CommentUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::post('comment/register', CommentCreateController::class)->name('comment.register');
    Route::delete('comment/{id}', CommentDeleteController::class)->name('comment.delete');
    Route::get('comment/{id}', CommentGetController::class)->name('comment.get');
    Route::get('comment', CommentListController::class)->name('comment.list');
    Route::put('comment/{id}', CommentUpdateController::class)->name('comment.update');
});
