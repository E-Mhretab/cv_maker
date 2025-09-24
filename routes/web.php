<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvManageController;

Route::get('/', [HomeController::class, 'index']);

// Rutas de auth
Route::get('login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rutas protegidas

// Rutas públicas para crear y ver CV
Route::resource('manage/cvs', CvManageController::class)->names([
    'index' => 'manage.cvs.index',
    'create' => 'manage.cvs.create',
    'store' => 'manage.cvs.store',
    'show' => 'manage.cvs.show',
    'edit' => 'manage.cvs.edit',
    'update' => 'manage.cvs.update',
    'destroy' => 'manage.cvs.destroy'
]);
Route::post('manage/cvs/create-form', [CvManageController::class, 'createForm'])->name('manage.cvs.createForm');

// Rutas protegidas solo para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('user_profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // Ejemplo: descarga de PDF
    Route::get('manage/cvs/{id}/export/pdf', [CvManageController::class, 'exportPdf'])->name('manage.cvs.export.pdf');
});
