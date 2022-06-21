<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectV2 extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function groupQuestions()
    {
        return $this->hasMany(GroupQuestion::class, 'subject_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'subjectId');
    }

    public function classes()
    {
        return $this->hasMany(Clazz::class, 'subject_id');
    }
}
