<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/test', [ProfileController::class, 'test']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('guest')->group(function () {
    // Display the login form
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Handle the login form submission
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

require __DIR__.'/auth.php';
