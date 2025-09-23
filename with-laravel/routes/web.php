<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ResumePdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('welcome');



// Authentication routes
require __DIR__.'/auth.php';

// Dashboard route - redirect based on user role
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('cvs.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Guest CV creation routes (no authentication required)
Route::get('/create-cv', [CvController::class, 'guestCreate'])->name('guest.cvs.create');
Route::post('/create-cv/form', [CvController::class, 'guestCreateForm'])->name('guest.cvs.create-form');
Route::post('/create-cv/store', [CvController::class, 'guestStore'])->name('guest.cvs.store');
Route::get('/cv/{cv}/view', [CvController::class, 'guestShow'])->name('guest.cvs.show');

// Public CV viewing route (no authentication required for public CVs)
Route::get('/cv/{cv}', [CvController::class, 'publicShow'])->name('cvs.public.show');

// Resource routes for CVs (Resumes) - Protected by authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/cvs/create-form', function () {
        return redirect()->route('cvs.create');
    });
    Route::post('/cvs/create-form', [CvController::class, 'createForm'])->name('cvs.create-form');
    Route::resource('cvs', CvController::class);
    Route::get('/cv/{cv}/download', [CvController::class, 'download'])->name('cvs.download');
    Route::get('/cv/{cv}/preview', [CvController::class, 'preview'])->name('cvs.preview');
    Route::get('/cv/{cv}/export', [CvController::class, 'export'])->name('cvs.export');
    Route::post('/cv/{cv}/publish', [CvController::class, 'publish'])->name('cvs.publish');

    // PDF Export routes
    Route::get('/cvs/{cv}/pdf', [ResumePdfController::class, 'download'])->name('cvs.pdf');
    Route::get('/cvs/{cv}/pdf/stream', [ResumePdfController::class, 'stream'])->name('cvs.pdf.stream');
});

// Resource routes for Users
Route::resource('users', UserController::class);

// Template routes
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/audit', [AdminController::class, 'audit'])->name('audit');
    Route::get('/sessions', [AdminController::class, 'sessions'])->name('sessions');
});

// Legacy URL redirects (301 Permanent Redirects)
// Main pages
Route::permanentRedirect('/index.php', '/');
Route::permanentRedirect('/login.php', '/login');
Route::permanentRedirect('/logout.php', '/logout');
Route::permanentRedirect('/user_profile.php', '/users/profile');

// CV Creation flow
Route::permanentRedirect('/create/cv_create.php', '/cvs/create');
Route::permanentRedirect('/create/cv_create_form.php', '/cvs/create');
Route::permanentRedirect('/create/cv_save.php', '/cvs');
Route::permanentRedirect('/create/cv_view.php', '/cvs');

// CV Management
Route::permanentRedirect('/manage/cv_list.php', '/cvs');
Route::permanentRedirect('/manage/cv_edit.php', '/cvs/{id}/edit');
Route::permanentRedirect('/manage/cv_delete.php', '/cvs/{id}');
Route::permanentRedirect('/manage/cv_preview.php', '/cvs/{id}/preview');
Route::permanentRedirect('/manage/cv_publish.php', '/cvs/{id}/publish');
Route::permanentRedirect('/manage/cv_export.php', '/cvs/{id}/export');
Route::permanentRedirect('/manage/cv_export_http.php', '/cvs/{id}/download');

// Template pages
Route::permanentRedirect('/esey.php', '/templates/esey');
Route::permanentRedirect('/nathan.php', '/templates/nathan');

// Admin routes
Route::permanentRedirect('/admin_dashboard.php', '/admin/dashboard');
Route::permanentRedirect('/check_database.php', '/admin/database');

// CV claiming routes
Route::permanentRedirect('/claim_cv.php', '/cvs/claim');
Route::permanentRedirect('/claim_guest_cv.php', '/cvs/claim-guest');

// Audit routes
Route::permanentRedirect('/audit/audit_viewer.php', '/admin/audit');
Route::permanentRedirect('/audit/debug_audit.php', '/admin/audit/debug');

// Session management
Route::permanentRedirect('/session_debug.php', '/admin/sessions');
Route::permanentRedirect('/setup_sessions_table.php', '/admin/sessions/setup');
