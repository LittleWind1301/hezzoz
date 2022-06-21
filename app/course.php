<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class course extends Model
{
    protected $guarded = [];
    use SoftDeletes;


    public function subjects()
    {
        return $this->hasMany(SubjectV2::class, 'course_id');
    }
}
