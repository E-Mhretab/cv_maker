<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public CV creation and viewing (no auth required)
Route::get('/cv/create', [CvController::class, 'templateSelection'])->name('cv.create');
Route::post('/cv/create/form', [CvController::class, 'createForm'])->name('cv.create.form');
Route::post('/cv', [CvController::class, 'store'])->name('cv.store');
Route::get('/cv/{cv}', [CvController::class, 'show'])->name('cv.show');

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CV management (except show, create, store which are public)
    Route::resource('cv', CvController::class)->except(['show', 'create', 'store']);
    Route::post('/cv/{cv}/publish', [CvController::class, 'publish'])->name('cv.publish');
    Route::post('/cv/{cv}/unpublish', [CvController::class, 'unpublish'])->name('cv.unpublish');
});
