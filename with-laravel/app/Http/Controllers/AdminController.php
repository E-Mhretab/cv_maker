<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\User;
use App\Models\CvMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_cvs' => Cv::count(),
            'total_users' => User::count(),
            'regular_users' => User::where('role', 'user')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        // Get CVs by template
        $cvsByTemplate = CvMetadata::select('template_type', DB::raw('count(*) as count'))
            ->groupBy('template_type')
            ->get()
            ->pluck('count', 'template_type')
            ->toArray();

        // Get recent CVs
        $recentCvs = Cv::with('metadata')
            ->join('cv_metadata', 'cv.id', '=', 'cv_metadata.cv_id')
            ->orderBy('cv_metadata.created_at', 'desc')
            ->limit(5)
            ->get(['cv.*', 'cv_metadata.template_type', 'cv_metadata.created_at as metadata_created_at']);

        // Convert the metadata_created_at to Carbon instances for proper formatting
        $recentCvs->transform(function ($cv) {
            if ($cv->metadata_created_at) {
                $cv->metadata_created_at = Carbon::parse($cv->metadata_created_at);
            }
            return $cv;
        });

        return view('admin.dashboard', compact('stats', 'cvsByTemplate', 'recentCvs'));
    }

    public function audit(Request $request)
    {
        $query = \App\Models\AuditLog::with('user')
            ->orderBy('timestamp', 'desc');

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('table')) {
            $query->where('table_name', $request->table);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('action', 'like', "%{$searchTerm}%")
                  ->orWhere('table_name', 'like', "%{$searchTerm}%")
                  ->orWhere('ip_address', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('username', 'like', "%{$searchTerm}%")
                               ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $auditLogs = $query->paginate(20)->withQueryString();

        // Get filter options
        $actions = \App\Models\AuditLog::distinct('action')->pluck('action')->sort();
        $tables = \App\Models\AuditLog::distinct('table_name')->pluck('table_name')->sort();
        $users = \App\Models\User::select('id', 'username', 'email')->get();

        // Get statistics
        $stats = [
            'total' => \App\Models\AuditLog::count(),
            'today' => \App\Models\AuditLog::whereDate('timestamp', today())->count(),
            'this_week' => \App\Models\AuditLog::whereBetween('timestamp', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => \App\Models\AuditLog::whereMonth('timestamp', now()->month)->count(),
        ];

        return view('admin.audit', compact('auditLogs', 'actions', 'tables', 'users', 'stats'));
    }

    public function sessions()
    {
        $sessions = \App\Models\UserSession::with('user')
            ->orderBy('last_activity', 'desc')
            ->paginate(20);

        $sessionStats = [
            'total' => \App\Models\UserSession::count(),
            'active' => \App\Models\UserSession::where('expires_at', '>', now())->count(),
            'expired' => \App\Models\UserSession::where('expires_at', '<=', now())->count(),
            'unique_users' => \App\Models\UserSession::distinct('user_id')->count('user_id'),
        ];

        // Debug information
        $debugInfo = [
            'sessions_with_users' => \App\Models\UserSession::whereNotNull('user_id')->count(),
            'sessions_without_users' => \App\Models\UserSession::whereNull('user_id')->count(),
            'sessions_with_device_id' => \App\Models\UserSession::whereNotNull('device_id')->count(),
            'sessions_without_device_id' => \App\Models\UserSession::whereNull('device_id')->count(),
        ];

        return view('admin.sessions', compact('sessions', 'sessionStats', 'debugInfo'));
    }

    public function terminateSession(Request $request, $sessionId)
    {
        $session = \App\Models\UserSession::find($sessionId);
        
        if (!$session) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        $session->delete();
        
        return redirect()->back()->with('success', 'Session terminated successfully.');
    }

    public function terminateAllSessions(Request $request, $userId)
    {
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        \App\Models\UserSession::where('user_id', $userId)->delete();
        
        return redirect()->back()->with('success', 'All sessions for user terminated successfully.');
    }

    public function terminateExpiredSessions()
    {
        $expiredCount = \App\Models\UserSession::where('expires_at', '<=', now())->count();
        \App\Models\UserSession::where('expires_at', '<=', now())->delete();
        
        return redirect()->back()->with('success', "Terminated {$expiredCount} expired sessions.");
    }

    public function getTemplateDisplayName($templateType)
    {
        $templates = [
            0 => 'Default Template',
            1 => 'Esey Template',
            2 => 'Nathan Template',
            3 => 'Mirian Template',
        ];

        return $templates[$templateType] ?? 'Unknown Template';
    }
}
