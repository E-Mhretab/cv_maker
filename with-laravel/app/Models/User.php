<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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
        'name',
        'email',
        'password',
        'password_hash',
        'role',
        'is_active',
        'last_login',
        'profile_photo',
        'google_drive_file_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'password_hash',
        'remember_token',
        'google_access_token',
        'google_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_login' => 'datetime',
            'google_token_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the CVs for the user.
     */
    public function cvs()
    {
        return $this->hasMany(Cv::class);
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get the user sessions for the user.
     */
    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     */
    public function getPasswordAttribute()
    {
        return $this->password_hash;
    }

    /**
     * Set the password for the user.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }

    /**
     * Find a user by their username.
     */
    public static function findByUsername($username)
    {
        return static::where('username', $username)->first();
    }

    /**
     * Find a user by their email.
     */
    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return null;
    }

    /**
     * Get the name attribute (alias for username).
     */
    public function getNameAttribute()
    {
        return $this->username;
    }

    /**
     * Set the name attribute (alias for username).
     */
    public function setNameAttribute($value)
    {
        $this->attributes['username'] = $value;
    }

    /**
     * Get the Google Drive web view URL.
     */
    public function getGoogleDriveWebLinkAttribute()
    {
        if ($this->google_drive_file_id) {
            return "https://drive.google.com/file/d/{$this->google_drive_file_id}/view";
        }
        return null;
    }

    /**
     * Get the Google Drive edit URL.
     */
    public function getGoogleDriveEditLinkAttribute()
    {
        if ($this->google_drive_file_id) {
            return "https://drive.google.com/file/d/{$this->google_drive_file_id}/edit";
        }
        return null;
    }

    /**
     * Get the Google Drive download URL.
     */
    public function getGoogleDriveDownloadLinkAttribute()
    {
        if ($this->google_drive_file_id) {
            return "https://drive.google.com/uc?export=download&id={$this->google_drive_file_id}";
        }
        return null;
    }

    /**
     * Check if profile photo is synced to Google Drive.
     */
    public function hasGoogleDrivePhoto()
    {
        return !empty($this->google_drive_file_id);
    }

    /**
     * Check if user has valid Google OAuth token.
     */
    public function hasValidGoogleToken(): bool
    {
        return !empty($this->google_refresh_token) 
            && (!$this->google_token_expires_at || $this->google_token_expires_at->isFuture());
    }

    /**
     * Check if user is connected to Google Drive.
     */
    public function isGoogleDriveConnected(): bool
    {
        return !empty($this->google_refresh_token);
    }
}
