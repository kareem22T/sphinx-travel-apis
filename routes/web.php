<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HotelController;
use App\Http\Controllers\User\TourController;
use App\Http\Controllers\User\RestaurantController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\MessangerController;
use App\Http\Controllers\User\CarController;
use App\Http\Controllers\User\RateController;
Route::get('/', function () {
    return view('welcome');
});

Route::get("/get-hotels", [HotelController::class, "getHotels"]);
Route::get("/get-cars", [CarController::class, "getCars"]);
Route::get("/get-cottages", [HotelController::class, "getCottages"]);
Route::get("/get-hotel-restaurent", [HotelController::class, "getHotelNearstRestaurante"]);
Route::get("/get-tours", [TourController::class, "getTours"]);
Route::get("/get-tour", [TourController::class, "getTour"]);
Route::get("/get-resturante", [RestaurantController::class, "nearestRestaurants"]);
Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);
Route::middleware('auth:sanctum')->post("/get-user-notifications", [UserController::class, "getUserNotifications"]);
Route::middleware('auth:sanctum')->post("/book", [BookingController::class, "create"]);
Route::middleware('auth:sanctum')->get("/get-bookings", [BookingController::class, "get"]);
Route::middleware('auth:sanctum')->post('/get-user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/send-msg', [MessangerController::class, 'send']);
Route::middleware('auth:sanctum')->post('/rate-hotel', [RateController::class, 'rateHotel']);
Route::middleware('auth:sanctum')->get('/get-user-messages', [MessangerController::class, 'userMesages']);
