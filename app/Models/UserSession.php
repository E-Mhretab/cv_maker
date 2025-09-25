<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id', 'user_id', 'ip_address', 'user_agent', 'device_id', 'refresh_token',
        'expires_at', 'last_activity', 'created_at'
    ];
    protected $casts = [
        'expires_at' => 'datetime',
        'last_activity' => 'datetime',
        'created_at' => 'datetime',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
