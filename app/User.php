<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class, 'user_roles', 'userId', 'roleId');
    }

    public function faculties(){
        return $this->belongsToMany(faculty::class, 'user_faculties', 'user_id', 'faculty_id');
    }

    public function classes(){
        return $this->belongsToMany(Clazz::class, 'student_classes', 'student_id', 'class_id');
    }

    public function profileStudent(){
        return $this->hasOne(ProfileStudent::class, 'user_id', 'id');
    }

    public function profileLecturers(){
        return $this->hasOne(ProfileLecturers::class, 'user_id', 'id');
    }

    public function checkPermissionAccess($permissionCheck){
        $roles = auth()->user()->roles;
        foreach($roles as $role){
            $permissions = $role->permissions;
            if($permissions->contains('key_code', $permissionCheck)){
                return true;
            }
        }
        return false;
    }

}
