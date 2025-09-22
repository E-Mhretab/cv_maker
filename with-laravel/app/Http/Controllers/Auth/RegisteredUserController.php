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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        \Log::info('Registration attempt:', $request->all());
        
        try {
            // Handle both 'name' and 'username' fields for compatibility
            $username = $request->username ?? $request->name;
            
            // Create validation rules based on what fields are present
            $rules = [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
            ];
            
            // Add username validation based on which field is present
            if ($request->has('username')) {
                $rules['username'] = 'required|string|max:255|unique:users';
            } else {
                $rules['name'] = 'required|string|max:255|unique:users,username';
            }
            
            $request->validate($rules);

            $user = User::create([
                'username' => $username,
                'email' => $request->email,
                'password' => $request->password, // The User model will hash this automatically
                'role' => 'user',
                'is_active' => true,
            ]);
            
            \Log::info('User created successfully:', ['user_id' => $user->id, 'username' => $user->username]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            \Log::error('Registration failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['registration' => 'Registration failed. Please try again.'])->withInput();
        }
    }
}
