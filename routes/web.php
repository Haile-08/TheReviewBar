<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::get('/movies/search', [PostController::class, 'search'])->name('movies.search');
});

Route::resource('post', PostController::class)->except('index');

require __DIR__.'/auth.php';
