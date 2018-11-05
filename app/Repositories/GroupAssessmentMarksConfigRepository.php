<?php

namespace App\Repositories;

use App\GroupAssessmentMarksConfig;

class GroupAssessmentMarksConfigRepository extends BaseRepository
{
    public function __construct(GroupAssessmentMarksConfig $model)
    {
        parent::__construct($model);
    }
}
