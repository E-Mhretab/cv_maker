<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Get user before session regenerate
        $user = Auth::user();
        
        // Regenerate session for security (like old PHP code)
        $request->session()->regenerate();

        // Update last login time (like old PHP code)
        $user->update(['last_login' => now()]);

        // Set session variables (like old PHP code)
        $request->session()->put([
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'login_time' => time(),
        ]);

        // Redirect based on user role (like old PHP version)
        $username = $user->username ?? $user->email ?? 'User';
        if ($user->isAdmin()) {
            return redirect()->intended(route('home', absolute: false))->with('status', 'Welcome back, ' . $username . '!');
        } else {
            return redirect()->intended(route('home', absolute: false))->with('status', 'Welcome back, ' . $username . '!');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have been successfully logged out.');
    }
}
