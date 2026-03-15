<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;

Route::inertia('/', 'Home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard/Dashboard')->name('dashboard');
});

require __DIR__.'/auth.php';