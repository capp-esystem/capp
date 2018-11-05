<?php

namespace App\Repositories;

use App\IntraGroupAssessmentMarksConfig;

class IntraGroupAssessmentMarksConfigRepository extends BaseRepository
{
    public function __construct(IntraGroupAssessmentMarksConfig $model)
    {
        parent::__construct($model);
    }
}
