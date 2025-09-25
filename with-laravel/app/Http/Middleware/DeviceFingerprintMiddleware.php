<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DeviceFingerprintService;
use App\Models\UserSession;

class DeviceFingerprintMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only process for authenticated users
        if (auth()->check()) {
            $this->updateSessionWithDeviceInfo($request);
        }
        
        return $next($request);
    }
    
    /**
     * Update the current session with device information
     */
    private function updateSessionWithDeviceInfo(Request $request)
    {
        $sessionId = session()->getId();
        $userId = auth()->id();
        
        // Generate device ID
        $deviceId = DeviceFingerprintService::generateDeviceId($request);
        
        // Get device information
        $deviceInfo = DeviceFingerprintService::getDeviceInfo($request->header('User-Agent', ''));
        
        // Update or create session record
        UserSession::updateOrCreate(
            ['id' => $sessionId],
            [
                'user_id' => $userId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent', ''),
                'device_id' => $deviceId,
                'last_activity' => now(),
                'expires_at' => now()->addMinutes(config('session.lifetime', 120)),
                'refresh_token' => null, // Can be used for token-based auth later
            ]
        );
    }
}
