<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hobby extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hobbies';

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
        'hobby_name',
        'description',
    ];

    /**
     * Get the CV that owns the hobby.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
