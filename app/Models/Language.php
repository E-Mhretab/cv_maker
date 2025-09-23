<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Language extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'cv_id',
        'language_name',
        'proficiency',
    ];

    protected $casts = [
        'proficiency' => 'string',
    ];

    /**
     * Get the CV that owns the language.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Get proficiency level options.
     */
    public static function getProficiencyLevels(): array
    {
        return [
            'basic' => 'Basic',
            'conversational' => 'Conversational',
            'fluent' => 'Fluent',
            'native' => 'Native',
        ];
    }

    /**
     * Get proficiency display name.
     */
    public function getProficiencyDisplayAttribute(): string
    {
        return self::getProficiencyLevels()[$this->proficiency] ?? $this->proficiency;
    }
}