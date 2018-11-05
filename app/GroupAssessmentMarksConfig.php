<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAssessmentMarksConfig extends Model
{
    protected $table = 'gpasg_marks_configs';

    protected $fillable = ['asg_id', 'moderate_method', 'lower_limit', 'upper_limit', 'penalty'];
}
