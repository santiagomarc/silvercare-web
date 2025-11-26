<?php

use App\Http\Controllers\Auth\CaregiverSetPasswordController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CaregiverDashboardController;
use App\Http\Controllers\CaregiverProfileController;
use App\Http\Controllers\CaregiverAnalyticsController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ElderlyDashboardController;
use Illuminate\Support\Facades\Route;

// Welcome landing page - redirect logged-in users to their dashboard
Route::get('/', function () {
    return view('welcome');
})->middleware('role.redirect')->name('welcome');

// Caregiver Password Setup (Signed Route - No Auth Required)
Route::get('/caregiver/set-password/{userId}', [CaregiverSetPasswordController::class, 'show'])
    ->name('caregiver.password.show');

Route::post('/caregiver/set-password/{userId}', [CaregiverSetPasswordController::class, 'store'])
    ->name('caregiver.password.store');

// Elderly Routes - Protected by 'elderly' middleware
Route::middleware(['auth', 'verified', 'elderly'])->group(function () {
    Route::get('/dashboard', [ElderlyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-medications', [ElderlyDashboardController::class, 'medications'])->name('elderly.medications');
    Route::get('/my-checklists', [ElderlyDashboardController::class, 'checklists'])->name('elderly.checklists');
    Route::post('/my-checklists/{checklist}/toggle', [ElderlyDashboardController::class, 'toggleChecklist'])->name('elderly.checklists.toggle');
    
    // Medication dose tracking
    Route::post('/my-medications/{medication}/take', [ElderlyDashboardController::class, 'takeMedication'])->name('elderly.medications.take');
    Route::post('/my-medications/{medication}/undo', [ElderlyDashboardController::class, 'undoMedication'])->name('elderly.medications.undo');
});

// Caregiver Routes - Protected by 'caregiver' middleware
Route::middleware(['auth', 'verified', 'caregiver'])->prefix('caregiver')->name('caregiver.')->group(function () {
    Route::get('/dashboard', [CaregiverDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [CaregiverProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [CaregiverProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/analytics', [CaregiverAnalyticsController::class, 'index'])->name('analytics');
    
    Route::resource('medications', MedicationController::class);
    Route::resource('checklists', ChecklistController::class);
    Route::post('checklists/{checklist}/toggle', [ChecklistController::class, 'toggleComplete'])->name('checklists.toggle');
});

// Profile Completion Routes (for elderly users who haven't completed profile)
Route::middleware(['auth'])->group(function () {
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
