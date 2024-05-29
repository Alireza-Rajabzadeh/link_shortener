<?php

use App\Http\Controllers\LinksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('client')->group(function () {

    Route::prefix('links')->group(function () {
        Route::get('/', [App\Http\Controllers\LinksController::class, 'index'])->middleware('auth');
        Route::post('/', [App\Http\Controllers\LinksController::class, 'store'])->middleware('auth');
    });
});
