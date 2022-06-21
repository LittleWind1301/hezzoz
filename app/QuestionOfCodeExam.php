<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionOfCodeExam extends Model
{
    protected $guarded = [];
    public function options(){
        return $this->hasMany(OptionOfCodeExam::class, 'questionOfCodeExamId');
    }
}
