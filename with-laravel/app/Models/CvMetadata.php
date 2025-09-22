<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvMetadata extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cv_metadata';

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
        'cv_id',
        'template_type',
        'is_public',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the CV that owns the metadata.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
