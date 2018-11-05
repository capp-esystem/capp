<?php

namespace App\Repositories;

use App\GroupAssessment;

class GroupAssessmentRepository extends BaseRepository
{
    public function __construct(GroupAssessment $model)
    {
        parent::__construct($model);
    }
}
