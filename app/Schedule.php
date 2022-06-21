<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function attendances(){
        return $this->hasMany(Attendance::class, 'schedule_id');
    }
}
