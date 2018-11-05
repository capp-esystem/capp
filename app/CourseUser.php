<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    protected $table = 'courses_users';

    protected $fillable = ['user_id', 'course_id', 'group_id'];

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function course() {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }

    public function group() {
        return $this->hasOne('App\Group', 'id', 'group_id');
    }
}
