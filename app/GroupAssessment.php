<?php

namespace App;

use DateTime;
use App\Helpers\DateTimeHelper;
use Illuminate\Database\Eloquent\Model;

class GroupAssessment extends Model
{
    protected $table = 'gpasgs';

    protected $fillable = ['course_id', 'name', 'description', 'criteria_reference', 'peer_contribution_percentage', 'response_type', 'response_no_required', 'start_at', 'end_at'];

    public function course() {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }

    public function marks() {
        return $this->hasMany('App\GroupAssessmentTutorMark', 'asg_id');
    }

    public function userMarks() {
        return $this->hasMany('App\GroupAssessmentUserMark', 'asg_id');
    }

    public function responses() {
        return $this->hasMany('App\GroupAssessmentUserResponse', 'asg_id');
    }

    public function marksConfig() {
        return $this->hasOne('App\GroupAssessmentMarksConfig', 'asg_id');
    }

    public function getAvailableAttribute()
    {
        $start = new DateTime($this->start_at);
        $end = new DateTime($this->end_at);
        return DateTimeHelper::isNowBetween($start, $end);
    }
}
