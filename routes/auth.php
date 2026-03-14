<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function() {
    // register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    //login
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [RegisterController::class, 'store']);
});