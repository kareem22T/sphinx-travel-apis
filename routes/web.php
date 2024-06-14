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
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\DestinationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/get-home-rooms", [HotelController::class, "getRomms"]);
Route::get("/get-hotels", [HotelController::class, "getHotels"]);
Route::get("/get-hotels-search", [SearchController::class, "getHotels"]);
Route::get("/get-destinations", [DestinationController::class, "getDestinations"]);
Route::get("/get-home-hotels", [HotelController::class, "getHomeHotels"]);
Route::get("/get-home-ad", [SettingsController::class, "getHomeAd"]);
Route::get("/get-cars", [CarController::class, "getCars"]);
Route::get("/get-currencies", [CurrencyController::class, "getCurrencies"]);
Route::get("/get-cottages", [HotelController::class, "getCottages"]);
Route::get("/get-hotel-restaurent", [HotelController::class, "getHotelNearstRestaurante"]);
Route::get("/get-tours", [TourController::class, "getTours"]);
Route::get("/get-home-tours", [TourController::class, "getHomeTours"]);
Route::get("/get-tour", [TourController::class, "getTour"]);
Route::get("/get-tour-search", [SearchController::class, "getTours"]);
Route::get("/get-resturante", [RestaurantController::class, "nearestRestaurants"]);
Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);
Route::post("/send-token", [UserController::class, "sedToken"]);
Route::middleware('auth:sanctum')->post("/get-user-notifications", [UserController::class, "getUserNotifications"]);
Route::middleware('auth:sanctum')->post("/book", [BookingController::class, "create"]);
Route::middleware('auth:sanctum')->get("/get-bookings", [BookingController::class, "get"]);
Route::middleware('auth:sanctum')->post('/get-user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/update-profile', [UserController::class, 'updateUser']);
Route::middleware('auth:sanctum')->post('/change-password', [UserController::class, 'changePassword']);
Route::middleware('auth:sanctum')->post('/send-msg', [MessangerController::class, 'send']);
Route::middleware('auth:sanctum')->post('/rate-hotel', [RateController::class, 'rateHotel']);
Route::middleware('auth:sanctum')->post('/rate-tour', [RateController::class, 'rateTour']);
Route::middleware('auth:sanctum')->get('/get-user-messages', [MessangerController::class, 'userMesages']);


Route::post('/send-notification', [NotificationController::class, 'sendPushNotification']);
