<?php

use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Welcome landing page
Route::get('/', function () {
    return view('welcome');
});

// Elderly Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Caregiver Dashboard (placeholder - create later)
Route::get('/caregiver/dashboard', function () {
    return view('caregiver.dashboard');
})->middleware(['auth', 'verified'])->name('caregiver.dashboard');

// Profile Completion Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/completion', [ProfileCompletionController::class, 'show'])
        ->name('profile.completion');
    
    Route::post('/profile/completion', [ProfileCompletionController::class, 'store'])
        ->name('profile.completion.store');
    
    Route::get('/profile/completion/skip', [ProfileCompletionController::class, 'skip'])
        ->name('profile.completion.skip');
});

// User Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
