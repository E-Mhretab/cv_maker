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
        // Solo admins
        if (!Auth::check() || !Auth::user()->is_admin) {
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
        $logs = $query->orderByDesc('created_at')->paginate(50);
        $users = User::orderBy('username')->get();
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $tables = AuditLog::select('table_name')->distinct()->pluck('table_name');
        return view('audit.audit_viewer', compact('logs', 'users', 'actions', 'tables'));
    }
}
