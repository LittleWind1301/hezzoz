<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileLecturers extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
