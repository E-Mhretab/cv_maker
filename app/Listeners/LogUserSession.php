<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use App\Models\UserSession;

class LogUserSession
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $sessionId = session()->getId();
        $ip = Request::ip();
        $userAgent = Request::header('User-Agent');
        $deviceId = hash('sha256', $userAgent.$ip.session()->getId());
        $now = now();
        UserSession::updateOrCreate(
            ['id' => $sessionId],
            [
                'user_id' => $user->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device_id' => $deviceId,
                'refresh_token' => null, // Puedes implementarlo si tu app lo usa
                'expires_at' => $now->copy()->addHour(),
                'last_activity' => $now,
                'created_at' => $now,
            ]
        );
    }
}
