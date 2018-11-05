<?php

namespace App;

use DateTime;
use App\Helpers\DateTimeHelper;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = ['code', 'name', 'start_at', 'end_at'];

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function students()
    {
        return $this->hasMany('App\CourseUser');
    }

    public function getAvailableAttribute()
    {
        $start = new DateTime($this->start_at);
        $end = new DateTime($this->end_at);
        return DateTimeHelper::isNowBetween($start, $end);
    }

    public function groupAssessments()
    {
        return $this->hasMany('App\GroupAssessment');
    }

    public function intraGroupAssessments()
    {
        return $this->hasMany('App\IntraGroupAssessment');
    }
}
