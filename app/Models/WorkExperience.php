<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkExperience extends Model
{
    use HasFactory;

    protected $table = 'work_experience';

    protected $fillable = [
        'cv_id',
        'company_name',
        'position',
        'description',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the CV that owns the work experience.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Get the duration of the work experience.
     */
    public function getDurationAttribute(): string
    {
        $start = $this->start_date;
        $end = $this->is_current ? now() : $this->end_date;

        if (!$start || !$end) {
            return 'N/A';
        }

        $years = $start->diffInYears($end);
        $months = $start->diffInMonths($end) % 12;

        $duration = '';
        if ($years > 0) {
            $duration .= $years . ' ' . ($years === 1 ? 'year' : 'years');
        }
        if ($months > 0) {
            if ($duration) {
                $duration .= ' ';
            }
            $duration .= $months . ' ' . ($months === 1 ? 'month' : 'months');
        }

        return $duration ?: 'Less than a month';
    }

    /**
     * Get formatted date range.
     */
    public function getDateRangeAttribute(): string
    {
        $start = $this->start_date?->format('M Y');
        $end = $this->is_current ? 'Present' : ($this->end_date?->format('M Y') ?? 'N/A');

        return $start . ' - ' . $end;
    }
}