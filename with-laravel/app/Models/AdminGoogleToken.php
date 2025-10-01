<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminGoogleToken extends Model
{
    protected $fillable = [
        'key',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the singleton token record
     */
    public static function getToken(): ?self
    {
        return self::where('key', 'default')->first();
    }

    /**
     * Check if token is valid
     */
    public function isValid(): bool
    {
        return !empty($this->refresh_token) 
            && (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Check if connected to Google Drive
     */
    public static function isConnected(): bool
    {
        $token = self::getToken();
        return $token && !empty($token->refresh_token);
    }
}

