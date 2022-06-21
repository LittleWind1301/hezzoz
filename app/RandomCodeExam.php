<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RandomCodeExam extends Model
{
    protected $guarded = [];

    public function questions(){
        return $this->hasMany(QuestionOfCodeExam::class, 'codeExamId');
    }
}
