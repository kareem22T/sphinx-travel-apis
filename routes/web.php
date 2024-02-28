<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HotelController;
Route::get('/', function () {
    return view('welcome');
});

Route::get("/get-hotels", [HotelController::class, "getHotels"]);