<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\LanguageController;



// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(["auth:sanctum,admin"])->group(function () {
    // Currencies
    Route::prefix("currencies")->group(function () {
        Route::get("/", [CurrencyController::class, 'get']);
        Route::post("/add", [CurrencyController::class, 'add']);
        Route::put("/update", [CurrencyController::class, 'update']);
        Route::post("/delete", [CurrencyController::class, 'delete']);
    });

    // Languages
    Route::prefix("languages")->group(function () {
        Route::get("/", [LanguageController::class, 'get']);
        Route::post("/add", [LanguageController::class, 'add']);
        Route::put("/update", [LanguageController::class, 'update']);
        Route::post("/delete", [LanguageController::class, 'delete']);
    });
});