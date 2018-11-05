<?php

namespace App;

use DateTime;
use App\Helpers\DateTimeHelper;
use Illuminate\Database\Eloquent\Model;

class IntraGroupAssessment extends Model
{
    protected $table = 'intragpasgs';

    protected $fillable = ['course_id', 'name', 'description', 'start_at', 'end_at'];

    public function course() {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }

    public function criterias() {
        return $this->hasMany('App\Criteria', 'asg_id');
    }

    public function user_marks() {
        return $this->hasMany('App\IntraGroupAssessmentUserMark', 'asg_id');
    }

    public function marks() {
        return $this->hasMany('App\IntraGroupAssessmentTutorMark', 'asg_id');
    }

    public function marksConfig() {
        return $this->hasOne('App\IntraGroupAssessmentMarksConfig', 'asg_id');
    }

    public function getAvailableAttribute()
    {
        $start = new DateTime($this->start_at);
        $end = new DateTime($this->end_at);
        return DateTimeHelper::isNowBetween($start, $end);
    }
}
