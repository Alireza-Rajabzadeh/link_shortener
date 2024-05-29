<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{link:shortener_link}', [App\Http\Controllers\LinksController::class, 'redirectLink'])->middleware('auth');

