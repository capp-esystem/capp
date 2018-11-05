<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntraGroupAssessmentUserMark extends Model
{
    protected $table = 'intragpasg_user_marks';

    protected $fillable = ['asg_id', 'from_id', 'to_id', 'criteria_id', 'marks'];

    public function fromUser() {
        return $this->hasOne('App\CourseUser', 'id', 'from_id');
    }

    public function toUser() {
        return $this->hasOne('App\CourseUser', 'id', 'to_id');
    }

    public function criteria() {
        return $this->hasOne('App\Criteria', 'id', 'criteria_id');
    }
}
