<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class faculty extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function classes(){
        return $this->hasMany(Clazz::class, 'faculty_id');
    }

    public function courses(){
        return $this->hasMany(course::class, 'faculty_id');
    }
}
