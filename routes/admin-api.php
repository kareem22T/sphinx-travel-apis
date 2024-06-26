<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ReasonController;
use App\Http\Controllers\Admin\ResturantController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\MessangerController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ActivitiesController;



// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

// Route::middleware(["auth:sanctum,admin"])->group(function () {
    // Currencies
    Route::prefix("requests")->group(function () {
        Route::get("/", [BookingController::class, 'get']);
        Route::get("/new", [BookingController::class, 'getNew']);
        Route::get("/seen", [BookingController::class, 'seen']);
        Route::post("/approve", [BookingController::class, 'approve']);
        Route::post("/cancel", [BookingController::class, 'cancel']);
    });

    Route::post("/push-notification-all", [MessangerController::class, 'pushNotificationToAll']);
    Route::prefix("chats")->group(function () {
        Route::post("/send", [MessangerController::class, 'send']);
        Route::get("/chats", [MessangerController::class, 'getChats']);
        Route::post("/chat", [MessangerController::class, 'getChat']);
        Route::post("/test", [MessangerController::class, 'testPush']);
    });

    // Currencies
    Route::prefix("currencies")->group(function () {
        Route::get("/", [CurrencyController::class, 'get']);
        Route::post("/add", [CurrencyController::class, 'add']);
        Route::put("/update", [CurrencyController::class, 'update']);
        Route::post("/delete", [CurrencyController::class, 'delete']);
    });

    // Destinations
    Route::prefix("destinations")->group(function () {
        Route::get("/", [DestinationController::class, 'get']);
        Route::post("/add", [DestinationController::class, 'add']);
        Route::post("/update", [DestinationController::class, 'update']);
        Route::post("/delete", [DestinationController::class, 'delete']);
    });

    // activities
    Route::prefix("activities")->group(function () {
        Route::get("/", [ActivitiesController::class, 'get']);
        Route::get("/activity", [ActivitiesController::class, 'activity']);
        Route::post("/add", [ActivitiesController::class, 'add']);
        Route::post("/update", [ActivitiesController::class, 'update']);
        Route::post("/delete", [ActivitiesController::class, 'delete']);
    });

    // Languages
    Route::prefix("languages")->group(function () {
        Route::get("/", [LanguageController::class, 'get']);
        Route::post("/add", [LanguageController::class, 'add']);
        Route::put("/update", [LanguageController::class, 'update']);
        Route::post("/delete", [LanguageController::class, 'delete']);
    });

    // Hotels
    Route::prefix("hotels")->group(function () {
        Route::get("/", [HotelController::class,'get']);
        Route::post("/add", [HotelController::class,'create']);
        Route::post("/update", [HotelController::class,'update']);
        Route::post("/delete", [HotelController::class,'delete']);
        Route::post("/hotel", [HotelController::class,'hotel']);
        Route::post("/room", [HotelController::class,'room']);
        Route::post("/room/add", [HotelController::class,'createRoom']);
        Route::post("/room/update", [HotelController::class,'updateRoom']);
        Route::post("/room/delete", [HotelController::class,'deleteRoom']);
    });

    // Tours
    Route::prefix("tours")->group(function () {
        Route::get("/", [TourController::class,'get']);
        Route::post("/add", [TourController::class,'create']);
        Route::post("/delete", [TourController::class,'delete']);
        Route::post("/tour", [TourController::class,'tour']);
        Route::post("/update", [TourController::class,'update']);
    });

    // Cars
    Route::prefix("cars")->group(function () {
        Route::get("/", [CarController::class,'get']);
        Route::post("/add", [CarController::class,'create']);
        Route::get("/features", [CarController::class,'getFeatures']);
        Route::post("/features/add", [CarController::class,'addFeature']);
        Route::post("/features/update", [CarController::class,'updateFeature']);
        Route::post("/features/delete", [CarController::class,'deleteFeature']);
        Route::post("/delete", [CarController::class,'delete']);
        Route::post("/car", [CarController::class,'car']);
        Route::post("/update", [CarController::class,'update']);
    });

    // Resturants
    Route::prefix("resturants")->group(function () {
        Route::get("/", [ResturantController::class,'get']);
        Route::post("/add", [ResturantController::class,'create']);
        Route::post("/delete", [ResturantController::class,'delete']);
        Route::post("/resturant", [ResturantController::class,'resturant']);
        Route::post("/update", [ResturantController::class,'update']);
    });

    // Features
    Route::prefix("features")->group(function () {
        Route::get("/", [FeatureController::class,'get']);
        Route::post("/add", [FeatureController::class,'add']);
        Route::post("/update", [FeatureController::class,'update']);
        Route::post("/delete", [FeatureController::class,'delete']);
    });

    // Reasons
    Route::prefix("reasons")->group(function () {
        Route::get("/", [ReasonController::class,'get']);
        Route::post("/add", [ReasonController::class,'add']);
        Route::post("/update", [ReasonController::class,'update']);
        Route::post("/delete", [ReasonController::class,'delete']);
    });
    // Rating
    Route::prefix("ratings")->group(function () {
        Route::get("/", [RateController::class,'getUnApproved']);
        Route::post("/approve", [RateController::class,'Approve']);
        Route::post("/reject", [RateController::class,'Reject']);
    });
    // Edit Home Data
    Route::prefix("settings")->group(function () {
        Route::post("/set-home-tours", [SettingsController::class,'setHomeTours']);
        Route::get("/get-home-tours", [SettingsController::class,'getHomeTours']);
        Route::post("/set-home-hotels", [SettingsController::class,'setHomeHotels']);
        Route::get("/get-home-hotels", [SettingsController::class,'getHomeHotels']);
        Route::post("/set-home-ad", [SettingsController::class,'setHomeAd']);
        Route::get("/get-home-ad", [SettingsController::class,'getHomeAd']);
        Route::post("/set-home-ad2", [SettingsController::class,'setHomeAd2']);
        Route::get("/get-home-ad2", [SettingsController::class,'getHomeAd2']);
    });
// });
