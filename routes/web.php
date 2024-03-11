<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HotelController;
use App\Http\Controllers\User\TourController;
Route::get('/', function () {
    return view('welcome');
});

Route::get("/get-hotels", [HotelController::class, "getHotels"]);
Route::get("/get-tours", [TourController::class, "getTours"]);