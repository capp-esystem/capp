<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAssessmentUserResponse extends Model
{
    protected $table = 'gpasg_user_responses';

    protected $fillable = ['asg_id', 'from_id', 'group_id', 'content'];

    public function fromUser() {
        return $this->hasOne('App\CourseUser', 'id', 'from_id');
    }

    public function group() {
        return $this->hasOne('App\Group', 'id', 'group_id');
    }
}
