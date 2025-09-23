<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'is_active',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'last_login' => 'datetime',
        ];
    }

    /**
     * Get the password attribute.
     */
    public function getPasswordAttribute()
    {
        return $this->password_hash;
    }

    /**
     * Set the password attribute.
     */
    public function setPasswordAttribute($value)
    {
        $this->password_hash = bcrypt($value);
    }

    /**
     * Get the CVs for the user.
     */
    public function cvs(): HasMany
    {
        return $this->hasMany(Cv::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is guest.
     */
    public function isGuest(): bool
    {
        return $this->role === 'guest' || !$this->is_active;
    }

    /**
     * Get user's public CVs.
     */
    public function publicCvs(): HasMany
    {
        return $this->cvs()->whereHas('metadata', function ($query) {
            $query->where('is_public', true);
        });
    }
}
