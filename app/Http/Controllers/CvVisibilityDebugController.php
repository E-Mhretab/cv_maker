<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvVisibilityDebugController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Captura la salida exacta del script legacy
        ob_start();
        include base_path('legacy/debug_cv_visibility.php'); // Ajusta la ruta si es necesario
        $content = ob_get_clean();

        return response($content);
    }
}
