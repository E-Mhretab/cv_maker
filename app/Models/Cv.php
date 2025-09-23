<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cv extends Model
{
    use HasFactory;

    protected $table = 'cv';

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'date_of_birth',
        'linkedin_profile',
        'portfolio',
        'profile_summary',
        'user_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the CV.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the CV metadata.
     */
    public function metadata(): HasOne
    {
        return $this->hasOne(CvMetadata::class);
    }

    /**
     * Get the skills for the CV.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * Get the languages for the CV.
     */
    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }

    /**
     * Get the work experience for the CV.
     */
    public function workExperience(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    /**
     * Get the education for the CV.
     */
    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Scope to get only public CVs.
     */
    public function scopePublic($query)
    {
        return $query->whereHas('metadata', function ($q) {
            $q->where('is_public', true);
        });
    }

    /**
     * Scope to get CVs by template type.
     */
    public function scopeByTemplate($query, $templateType)
    {
        return $query->whereHas('metadata', function ($q) use ($templateType) {
            $q->where('template_type', $templateType);
        });
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

        return $templateMap[$this->metadata?->template_type] ?? 'nathan';
    }

    /**
     * Check if CV is public.
     */
    public function isPublic(): bool
    {
        return $this->metadata?->is_public ?? false;
    }

    /**
     * Check if CV is published.
     */
    public function isPublished(): bool
    {
        return $this->metadata?->published_at !== null;
    }
}