<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Para sesiones de Laravel

class AuditDebugController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Exporta sesiones de Laravel a $_SESSION para compatibilidad legacy
        $_SESSION = Session::all();

        // Chequea si el archivo existe (para depurar)
        $legacyFile = base_path('legacy/debug_audit.php');
        if (!file_exists($legacyFile)) {
            return "Error: Archivo legacy no encontrado en " . $legacyFile;
        }

        // Captura la salida del script legacy
        ob_start();
        include $legacyFile;
        $content = ob_get_clean();

        // Devuelve el contenido como respuesta HTML
        return response($content);
    }
}
