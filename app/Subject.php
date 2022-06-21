<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function groupQuestions()
    {
        return $this->hasMany(GroupQuestion::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'subjectId');
    }
}
