<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function groupQuestion()
    {
        return $this->belongsTo(GroupQuestion::class, 'group_question_id');
    }

    public function questionOptions()
    {
        return $this->hasMany(QuestionOption::class, 'questionId');
    }
}
