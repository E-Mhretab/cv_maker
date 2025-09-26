<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuditLogger; // Agregado para logging

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
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Log successful login
            AuditLogger::logAuth('LOGIN', $user->id);

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
            'reg_password' => 'required|min:6|confirmed',
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
        $userId = Auth::id();

        Auth::logout();

        // Log logout
        AuditLogger::logAuth('LOGOUT', $userId);

        return redirect('/')->with('message', 'logged_out');
    }

    public function profile()
    {
        $user = Auth::user();

        $cvs = \App\Models\Cv::leftJoin('cv_metadata as m', 'cv.id', '=', 'm.cv_id')
            ->select('cv.*', 'm.created_at', 'm.template_type', 'm.is_public', 'm.published_at')
            ->where('cv.user_id', $user->id)
            ->orderByDesc('m.created_at')
            ->get();

        return view('user_profile', compact('user', 'cvs'));
    }
}
