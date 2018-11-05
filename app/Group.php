<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = ['name', 'course_id'];

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }

    public function members()
    {
        return $this->hasMany('App\CourseUser');
    }
     
}
