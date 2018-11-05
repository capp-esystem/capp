<?php

namespace App\Repositories;
use App\GroupAssessmentTutorMark;

class GroupAssessmentTutorMarkRepository extends BaseRepository
{
    public function __construct(GroupAssessmentTutorMark $model)
    {
        parent::__construct($model);
    }
}
