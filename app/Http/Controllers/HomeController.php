<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;           // Modelo CV
use App\Models\CvMetadata;   // Si lo necesitas en otras consultas
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /* -----------------------------------------------------------------
         | 1. Construir el contexto de usuario (si está logueado)
         * -----------------------------------------------------------------*/
        $user = Auth::user();          // null si nadie ha iniciado sesión

        $userContext = [
            'is_logged_in' => (bool) $user,          // true/false
            'user'         => $user,                 // objeto User o null
            'is_admin'     => $user->is_admin ?? false,
            'role'         => $user->role ?? 'user', // “admin” o “user” (fallback)
        ];

        /* -----------------------------------------------------------------
         | 2. Si es admin y NO viene con  ?view_public, lo mandamos al dashboard
         * -----------------------------------------------------------------*/
        if ($userContext['is_admin'] && ! $request->has('view_public')) {
            return redirect('/admin_dashboard');     // ajusta la ruta si tuvieras otra
        }

        /* -----------------------------------------------------------------
         | 3. Traer los CVs públicos para la galería de la home
         * -----------------------------------------------------------------*/
        $cvs = Cv::join('cv_metadata as m', 'cv.id', '=', 'm.cv_id')
                 ->where('m.is_public', 1)
                 ->orderByDesc('m.published_at')
                 ->select('cv.id', 'cv.name', 'cv.profile_summary')
                 ->get();

        /* -----------------------------------------------------------------
         | 4. Renderizar la vista pasando $userContext y $cvs
         * -----------------------------------------------------------------*/
        return view('index', compact('userContext', 'cvs'));
    }
}
