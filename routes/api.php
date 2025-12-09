<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;

Route::apiResource('/places', PlaceController::class);
Route::apiResource('/tours', TourController::class);
Route::apiResource('/bookings', BookingController::class);
Route::apiResource('/reviews', ReviewController::class);
Route::get('/users', [UserController::class, 'index']);
