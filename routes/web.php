<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/venue', [VenueController::class, 'index'])->name('venue');
Route::get('/v/{slug}', [VenueController::class, 'detail'])->name('venue.detail');

Route::get('/field/{id}', [FieldController::class, 'detail'])->name('field.detail');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('postRegister');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('postLogin');
    Route::get('/verify-register', [AuthController::class, 'showVerifyRegister'])->name('verifyRegister');
    Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify-code');
});

Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('homeDashboard');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::get('/users', [UsersController::class, 'index'])->name('users');
    });

    Route::post('/book', [BookingController::class, 'book'])->name('book');
    Route::post('/confirm-payment/{transactionId}', [BookingController::class, 'confirmPayment'])->name('confirmPayment');
});
