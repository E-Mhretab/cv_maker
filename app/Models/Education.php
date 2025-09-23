<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv_id',
        'institution_name',
        'degree',
        'field_of_study',
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
     * Get the CV that owns the education.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Get the duration of the education.
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

    /**
     * Get full degree name.
     */
    public function getFullDegreeAttribute(): string
    {
        $degree = $this->degree;
        if ($this->field_of_study) {
            $degree .= ' in ' . $this->field_of_study;
        }

        return $degree;
    }
}