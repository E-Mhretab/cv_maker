<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv; // Asegúrate de que el modelo Cv existe
use App\Models\CvMetadata; // Asegúrate de que el modelo CvMetadata existe

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Obtener contexto de usuario
        $userContext = auth()->check() ? [
            'is_logged_in' => true,
            'user' => auth()->user(),
            'is_admin' => auth()->user()->is_admin ?? false  // Valor por defecto si no existe en el modelo User
        ] : [
            'is_logged_in' => false,
            'is_admin' => false  // Agregado para evitar el error en no logueados
        ];


        // Redirigir admins si no hay ?view_public (adaptado a Laravel)
        if ($userContext['is_admin'] && !$request->has('view_public')) {
            return redirect('/admin_dashboard'); // Ajusta la ruta si es necesario
        }

        // Consulta de CVs publicados (usando Eloquent con nombre de tabla correcto)
        $cvs = Cv::join('cv_metadata as m', 'cv.id', '=', 'm.cv_id')
            ->where('m.is_public', 1)
            ->orderByDesc('m.published_at')
            ->select('cv.id', 'cv.name', 'cv.profile_summary')
            ->get();


        // Pasar variables a la vista
        return view('index', compact('userContext', 'cvs'));
    }
}
