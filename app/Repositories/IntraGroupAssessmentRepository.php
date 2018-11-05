<?php

namespace App\Repositories;

use App\IntraGroupAssessment;

class IntraGroupAssessmentRepository extends BaseRepository
{
    public function __construct(IntraGroupAssessment $model)
    {
        parent::__construct($model);
    }
}
