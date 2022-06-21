<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    protected $guarded = [];
    use SoftDeletes;


    public function questions(){
        return $this->belongsToMany(Question::class, 'detail_exams', 'examId', 'questionId');
    }

    public function classes(){
        return $this->belongsToMany(Clazz::class, 'exam_classes', 'examId', 'classId');
    }

    public function subject(){
        return $this->hasOne(SubjectV2::class, 'Id', 'subjectId');
    }

    public function randomCodeExams(){
        return $this->hasMany(RandomCodeExam::class, 'exam_id');
    }
}
