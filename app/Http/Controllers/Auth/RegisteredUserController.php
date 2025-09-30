<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email is already registered.',
        ]);

        // Hash password using password_hash (like old PHP code)
        $passwordHash = password_hash($request->password, PASSWORD_DEFAULT);

        // Create user (exactly like old PHP code)
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_hash' => $passwordHash,
            'role' => 'user',
            'is_active' => 1,
        ]);

        // Auto-login after registration (like old PHP code)
        Auth::login($user);

        // Update last login
        $user->update(['last_login' => now()]);

        return redirect()->route('home')->with('status', 'Registration successful! Welcome, ' . $user->username . '!');
    }
}