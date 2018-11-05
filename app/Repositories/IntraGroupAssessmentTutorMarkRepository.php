<?php

namespace App\Repositories;

use App\IntraGroupAssessmentTutorMark;

class IntraGroupAssessmentTutorMarkRepository extends BaseRepository
{
    public function __construct(IntraGroupAssessmentTutorMark $model)
    {
        parent::__construct($model);
    }
}
