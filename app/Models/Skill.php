<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'cv_id',
        'skill_name',
        'description',
    ];

    /**
     * Get the CV that owns the skill.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}