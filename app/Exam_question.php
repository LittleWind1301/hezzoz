<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam_question extends Model
{
    protected $fillable = ['exam_id',
                            'exam_question_title',
                            'exam_question_answer',
                            'mark'];
}
