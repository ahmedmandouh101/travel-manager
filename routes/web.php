<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/places', [PlaceController::class, 'index'])->name('web.places.index');
Route::get('/tours', [TourController::class, 'index'])->name('web.tours.index');
Route::get('/bookings', [BookingController::class, 'index'])->name('web.bookings.index');
Route::get('/reviews', [ReviewController::class, 'index'])->name('web.reviews.index');

