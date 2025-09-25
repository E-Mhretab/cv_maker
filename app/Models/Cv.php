<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $table = 'cv';

    public function metadata()
    {
        return $this->hasOne(CvMetadata::class, 'cv_id');
    }

    public function workExperiences()
    {
        return $this->hasMany(\App\Models\WorkExperience::class, 'cv_id');
    }

    public function education()
    {
        return $this->hasMany(\App\Models\Education::class, 'cv_id');
    }

    public function skills()
    {
        return $this->hasMany(\App\Models\Skill::class, 'cv_id');
    }

    public function languages()
    {
        return $this->hasMany(\App\Models\Language::class, 'cv_id');
    }

    public function hobbies()
    {
        return $this->hasMany(\App\Models\Hobby::class, 'cv_id');
    }
    
}
