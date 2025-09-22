<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cv extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cv';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the user that owns the CV.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the metadata for the CV.
     */
    public function metadata(): HasOne
    {
        return $this->hasOne(CvMetadata::class);
    }

    /**
     * Get the work experiences for the CV.
     */
    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    /**
     * Get the education records for the CV.
     */
    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
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
     * Get the hobbies for the CV.
     */
    public function hobbies(): HasMany
    {
        return $this->hasMany(Hobby::class);
    }
}
