<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/post', [PostController::class, 'store']);

    // movies
    Route::get('/movies/search', [PostController::class, 'search'])->name('movies.search');
});

require __DIR__.'/auth.php';
