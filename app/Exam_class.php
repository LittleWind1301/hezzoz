<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam_class extends Model
{
    protected $guarded = [];

    public function exams(){
        return $this->hasOne(Exam::class, 'id', 'examId');
    }
}
