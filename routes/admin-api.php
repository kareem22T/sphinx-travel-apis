<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;



// Auth Routes
Route::post('/login', [AuthController::class, 'login']);