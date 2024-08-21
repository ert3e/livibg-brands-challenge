<?php

use App\Http\Controllers\TvShowSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['throttle:10,1'])->group(function () {
    Route::get('/search', [TvShowSearchController::class, 'search']);
});
