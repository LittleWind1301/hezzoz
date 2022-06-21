<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileStudent extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function classes(){
        return $this->belongsToMany(Clazz::class, 'student_classes', 'student_id', 'class_id');
    }

}
