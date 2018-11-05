<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAssessmentTutorMark extends Model
{
    protected $table = "gpasg_tutor_marks";

    protected $fillable = ['asg_id', 'group_id', 'marks'];
}
