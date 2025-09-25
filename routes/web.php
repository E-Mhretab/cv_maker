<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvManageController;
use App\Http\Controllers\AdminDashboardController; 

Route::get('/', [HomeController::class, 'index']);

// Rutas de auth
Route::get('login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rutas protegidas para admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin_dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard'); // Nueva ruta agregada para coincidir con tu URL de prueba (sin romper la anterior)
    Route::get('/audit/logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.logs');
});


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
Route::get('manage/cv_preview/{id}', [CvManageController::class, 'renderCVTemplate'])->name('manage.cv_preview');


// Rutas protegidas solo para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('user_profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('manage/cv_export_pdf/{id}', [CvManageController::class, 'exportPdf'])->name('manage.cv_export_pdf');
    Route::get('manage/cv_export_xml/{id}', [CvManageController::class, 'exportXml'])->name('manage.cv_export_xml');
    Route::get('/session/management', [\App\Http\Controllers\SessionController::class, 'index'])->name('session.management');
    Route::post('/session/destroy', [\App\Http\Controllers\SessionController::class, 'destroySession'])->name('session.destroy');
    Route::post('/session/destroy-other', [\App\Http\Controllers\SessionController::class, 'destroyAllOther'])->name('session.destroyOther');
    Route::post('/session/destroy-all', [\App\Http\Controllers\SessionController::class, 'destroyAll'])->name('session.destroyAll');
    Route::get('/session/debug', [\App\Http\Controllers\SessionController::class, 'debug'])->name('session.debug');
});


