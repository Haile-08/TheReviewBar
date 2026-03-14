<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::middleware('guest')->group(function () {
    // register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // login
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);

    // google auth
    Route::get('/auth/redirect', function () {
        return Socialite::driver('google')->redirect();
    })->name('google');
    Route::get('/auth/callback', [AuthController::class, 'google']);
});

Route::middleware('auth')->group(function () {
    //logout
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    
});