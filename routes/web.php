<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/post', [PostController::class, 'store']);
    Route::delete('/post/{post}', [PostController::class, 'destroy']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');

    // movies
    Route::get('/movies/search', [PostController::class, 'search'])->name('movies.search');
});

require __DIR__.'/auth.php';
