<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupQuestion extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function questions()
    {
        return $this->hasMany(Question::class, 'group_question_id');
    }

    public function subject(){
        //return $this->hasOne(Subject::class);
        return $this->belongsTo(Subject::class, 'subject_id');
    }

}
