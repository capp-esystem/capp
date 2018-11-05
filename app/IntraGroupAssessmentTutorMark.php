<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntraGroupAssessmentTutorMark extends Model
{
    protected $table = 'intragpasg_tutor_marks';

    protected $fillable = ['asg_id', 'group_id', 'marks'];
}
