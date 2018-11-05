<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntraGroupAssessmentMarksConfig extends Model
{
    protected $table = 'intragpasg_marks_configs';

    protected $fillable = ['asg_id', 'pa_score_adjust_method', 'pa_score_adjust_value', 'max_cap', 'penalty'];
}
