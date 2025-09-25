<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;

class UpdateUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $sessionId = session()->getId();
            $session = UserSession::find($sessionId);
            if ($session) {
                $session->last_activity = now();
                $session->expires_at = now()->copy()->addHour();
                $session->save();
            }
        }
        return $next($request);
    }
}
