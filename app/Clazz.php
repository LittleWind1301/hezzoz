<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clazz extends Model
{
    protected $guarded = [];
    use SoftDeletes;


//    public function subjects(){
//        return $this->belongsToMany(Subject::class, 'subject_classes', 'class_id', 'subject_id');
//    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_classes', 'class_id', 'student_id');
    }

    public function faculty(){
        return $this->hasOne(faculty::class, 'id', 'faculty_id');
    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'class_id');
    }
}
