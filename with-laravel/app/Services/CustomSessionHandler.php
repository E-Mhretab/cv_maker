<?php

namespace App\Services;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomSessionHandler implements \SessionHandlerInterface
{
    /**
     * The number of minutes the session should be valid.
     */
    protected $minutes;

    /**
     * Create a new database session handler instance.
     */
    public function __construct($minutes = 120)
    {
        $this->minutes = $minutes;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $session = DB::table('user_sessions')
            ->where('id', $sessionId)
            ->where('expires_at', '>', now())
            ->first();

        if ($session) {
            // Update last activity
            DB::table('user_sessions')
                ->where('id', $sessionId)
                ->update(['last_activity' => now()]);

            return $session->payload ?? '';
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $payload = $this->getDefaultPayload($data);
        $expiresAt = now()->addMinutes($this->minutes);
        $userId = auth()->id(); // This will be null for guest users

        DB::table('user_sessions')->updateOrInsert(
            ['id' => $sessionId],
            [
                'user_id' => $userId, // NULL for guest users, user ID for authenticated users
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'payload' => $data,
                'expires_at' => $expiresAt,
                'last_activity' => now(),
                'created_at' => now(),
            ]
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        DB::table('user_sessions')->where('id', $sessionId)->delete();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        DB::table('user_sessions')
            ->where('expires_at', '<', now())
            ->delete();

        return true;
    }

    /**
     * Get the default payload for the session.
     */
    protected function getDefaultPayload($data)
    {
        return [
            'payload' => $data,
            'last_activity' => time(),
        ];
    }
}
