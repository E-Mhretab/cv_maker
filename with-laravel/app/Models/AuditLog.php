<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'timestamp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'timestamp' => 'datetime',
        ];
    }

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the action type in a human-readable format.
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'view' => 'Viewed',
            'export' => 'Exported',
            'print' => 'Printed',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get the action color for display.
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'view' => 'primary',
            'export' => 'info',
            'print' => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Get the table name in a human-readable format.
     */
    public function getTableLabelAttribute(): string
    {
        return match($this->table_name) {
            'cv' => 'CV',
            'users' => 'User',
            'cv_metadata' => 'CV Metadata',
            'education' => 'Education',
            'work_experience' => 'Work Experience',
            'skills' => 'Skills',
            'languages' => 'Languages',
            'hobbies' => 'Hobbies',
            default => ucfirst(str_replace('_', ' ', $this->table_name)),
        };
    }

    /**
     * Get formatted old values for display.
     */
    public function getFormattedOldValuesAttribute(): string
    {
        if (empty($this->old_values)) {
            return 'N/A';
        }

        if (is_array($this->old_values)) {
            return json_encode($this->old_values, JSON_PRETTY_PRINT);
        }

        return $this->old_values;
    }

    /**
     * Get formatted new values for display.
     */
    public function getFormattedNewValuesAttribute(): string
    {
        if (empty($this->new_values)) {
            return 'N/A';
        }

        if (is_array($this->new_values)) {
            return json_encode($this->new_values, JSON_PRETTY_PRINT);
        }

        return $this->new_values;
    }

    /**
     * Scope to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by table.
     */
    public function scopeByTable($query, $table)
    {
        return $query->where('table_name', $table);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [$startDate, $endDate]);
    }
}