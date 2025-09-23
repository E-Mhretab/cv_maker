<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvMetadata extends Model
{
    use HasFactory;

    protected $table = 'cv_metadata';

    protected $fillable = [
        'cv_id',
        'template_type',
        'is_public',
        'published_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the CV that owns the metadata.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Get template name from template type.
     */
    public function getTemplateNameAttribute(): string
    {
        $templateMap = [
            1 => 'nathan',
            2 => 'esey',
            3 => 'mirian',
        ];

        return $templateMap[$this->template_type] ?? 'nathan';
    }

    /**
     * Get template display name.
     */
    public function getTemplateDisplayNameAttribute(): string
    {
        $displayNames = [
            1 => 'Nathan Template',
            2 => 'Esey Template',
            3 => 'Mirian Template',
        ];

        return $displayNames[$this->template_type] ?? 'Unknown Template';
    }

    /**
     * Publish the CV.
     */
    public function publish(): void
    {
        $this->update([
            'is_public' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish the CV.
     */
    public function unpublish(): void
    {
        $this->update([
            'is_public' => false,
            'published_at' => null,
        ]);
    }
}