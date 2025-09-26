<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = AuditLog::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->action) {
            $query->where('action', $request->action);
        }

        if ($request->table_name) {
            $query->where('table_name', $request->table_name);
        }

        $debugQuery = null;
        $debugBindings = json_encode($query->getBindings(), JSON_PRETTY_PRINT);
        $debugWhere = null;
        if ($request->has('debug') && $request->debug) {
            $debugQuery = $query->toSql();
            $debugBindings = json_encode($query->getBindings(), JSON_PRETTY_PRINT);  // Convertido a string para evitar error
            $debugWhere = 'WHERE 1=1';
        }

        $logs = $query->orderByDesc('timestamp')->paginate(50);

        $users = User::orderBy('username')->get();
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $tables = AuditLog::select('table_name')->distinct()->pluck('table_name');

        return view('audit.audit_viewer', compact('logs', 'users', 'actions', 'tables', 'debugQuery', 'debugBindings', 'debugWhere'));
    }
}
