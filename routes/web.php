<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CvController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Test route to verify routing is working
Route::get('/test-route', function () {
    return 'Routes are working!';
});

// Simple test route without database
Route::get('/test-simple', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel is working!',
        'time' => now()->format('Y-m-d H:i:s')
    ]);
});

// Database test route
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        $userCount = App\Models\User::count();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful!',
            'user_count' => $userCount,
            'time' => now()->format('Y-m-d H:i:s')
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage(),
            'time' => now()->format('Y-m-d H:i:s')
        ]);
    }
});

// Test login route
Route::get('/test-login', function () {
    try {
        $user = User::where('username', 'admin')->first();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Admin user found',
                'username' => $user->username,
                'role' => $user->role,
                'has_password_hash' => !empty($user->password_hash),
                'password_check' => password_verify('admin123', $user->password_hash) ? 'SUCCESS' : 'FAILED'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin user not found'
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
});

// Test login form submission
Route::post('/test-login-form', function (Request $request) {
    try {
        $username = $request->input('email');
        $password = $request->input('password');
        
        // Use exact same query as LoginRequest
        $user = User::where(function($query) use ($username) {
            $query->where('username', $username)
                  ->orWhere('email', $username);
        })
        ->where('is_active', 1)
        ->first();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'username_searched' => $username
            ]);
        }
        
        if (!password_verify($password, $user->password_hash)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid password',
                'username' => $user->username
            ]);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Login would be successful',
            'username' => $user->username,
            'role' => $user->role,
            'is_active' => $user->is_active
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Public CV creation and viewing (no auth required)
Route::get('/cv/create', [CvController::class, 'templateSelection'])->name('cv.create');
Route::get('/cv/create/form', [CvController::class, 'createForm'])->name('cv.create.form');
Route::post('/cv/create/form', [CvController::class, 'createForm'])->name('cv.create.form.post');
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
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/cvs', [App\Http\Controllers\AdminController::class, 'cvList'])->name('admin.cv-list');
        Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'userList'])->name('admin.user-list');
    });
    
    // CV management routes for authenticated users
    Route::get('/cv', [App\Http\Controllers\CvController::class, 'index'])->name('cv.index');
    Route::get('/cv/{cv}/edit', [App\Http\Controllers\CvController::class, 'edit'])->name('cv.edit');
    Route::put('/cv/{cv}', [App\Http\Controllers\CvController::class, 'update'])->name('cv.update');
    Route::delete('/cv/{cv}', [App\Http\Controllers\CvController::class, 'destroy'])->name('cv.destroy');
    Route::post('/cv/{cv}/publish', [App\Http\Controllers\CvController::class, 'publish'])->name('cv.publish');
    Route::post('/cv/{cv}/unpublish', [App\Http\Controllers\CvController::class, 'unpublish'])->name('cv.unpublish');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CV management (individual routes to avoid conflicts)
    Route::get('/cv', [CvController::class, 'index'])->name('cv.index');
    Route::get('/cv/{cv}/edit', [CvController::class, 'edit'])->name('cv.edit');
    Route::put('/cv/{cv}', [CvController::class, 'update'])->name('cv.update');
    Route::delete('/cv/{cv}', [CvController::class, 'destroy'])->name('cv.destroy');
    Route::post('/cv/{cv}/publish', [CvController::class, 'publish'])->name('cv.publish');
    Route::post('/cv/{cv}/unpublish', [CvController::class, 'unpublish'])->name('cv.unpublish');
});
