<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,  // Asume auth por username
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Log successful login (migra tu AuditLogger aquí si quieres)

            // Redirigir basado en role
            if ($user->role === 'admin') {
                $redirect = $request->query('redirect', 'admin_dashboard');
            } else {
                $redirect = $request->query('redirect', 'manage/cvs');
            }

            return redirect($redirect);
        }

        return back()->withErrors(['username' => 'Credenciales incorrectas.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'reg_username' => 'required|string|min:3|max:50|unique:users,username',
            'reg_email' => 'required|email|unique:users,email',
            'reg_password' => 'required|min:6|confirmed',  // Confirma contra reg_password_confirmation
        ]);

        $user = User::create([
            'username' => $request->reg_username,
            'email' => $request->reg_email,
            'password_hash' => Hash::make($request->reg_password),
            'role' => 'user',
        ]);

        Auth::login($user);

        $redirect = $request->query('redirect', 'manage/cvs');

        return redirect($redirect)->with('success', '¡Registro exitoso! Has iniciado sesión.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('message', 'logged_out');
    }
}
