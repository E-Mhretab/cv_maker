<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = session()->getId();
        $sessions = UserSession::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->orderByDesc('last_activity')
            ->get();
        $success = session('success');
        $debug = $request->has('debug');
        $sessionData = session()->all();
        return view('session.session_management', compact('user', 'sessions', 'currentSessionId', 'success', 'debug', 'sessionData'));
    }

    public function destroySession(Request $request)
    {
        $sessionId = $request->input('session_id');
        if ($sessionId && $sessionId !== session()->getId()) {
            UserSession::where('id', $sessionId)->delete();
            return redirect()->route('session.management')->with('success', 'Session destroyed successfully.');
        }
        return back();
    }

    public function destroyAllOther(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = session()->getId();
        UserSession::where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();
        return redirect()->route('session.management')->with('success', 'All other sessions destroyed successfully.');
    }

    public function destroyAll(Request $request)
    {
        $user = Auth::user();
        UserSession::where('user_id', $user->id)->delete();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login')->with('message', 'All sessions destroyed.');
    }

    // Debug view similar to debug_sessions.php
    public function debug(Request $request)
    {
        $dbStatus = null;
        $tableExists = false;
        $tableStructure = [];
        $totalSessions = 0;
        $activeSessions = 0;
        $allSessions = [];
        $currentSessionId = session()->getId();
        $sessionData = session()->all();
        $user = Auth::user();
        $userSessions = [];
        try {
            DB::connection()->getPdo();
            $dbStatus = 'Database connection successful!';
            $tableExists = DB::select("SHOW TABLES LIKE 'user_sessions'");
            if ($tableExists) {
                $tableStructure = DB::select('DESCRIBE user_sessions');
                $totalSessions = DB::table('user_sessions')->count();
                $activeSessions = DB::table('user_sessions')->where('expires_at', '>', now())->count();
                $allSessions = DB::table('user_sessions')->orderByDesc('created_at')->limit(10)->get();
                if ($user) {
                    $userSessions = DB::table('user_sessions')->where('user_id', $user->id)->get();
                }
            }
        } catch (\Exception $e) {
            $dbStatus = 'Database connection failed!';
        }
        return view('session.debug_sessions', compact(
            'dbStatus', 'tableExists', 'tableStructure', 'totalSessions', 'activeSessions', 'allSessions', 'currentSessionId', 'sessionData', 'user', 'userSessions'
        ));
    }
}
