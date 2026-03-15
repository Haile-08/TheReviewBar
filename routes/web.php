<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard/Dashboard')->name('dashboard');
});

Route::resource('post', PostController::class);

require __DIR__.'/auth.php';
