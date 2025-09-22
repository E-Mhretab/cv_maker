<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'education';

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
        'degree',
        'institution',
        'education_start',
        'education_end',
        'is_current',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'education_start' => 'date',
            'education_end' => 'date',
            'is_current' => 'boolean',
        ];
    }

    /**
     * Get the CV that owns the education record.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
