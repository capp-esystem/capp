<?php

namespace App\Repositories;

use App\IntraGroupAssessmentUserMark;

class IntraGroupAssessmentUserMarkRepository extends BaseRepository
{
    public function __construct(IntraGroupAssessmentUserMark $model)
    {
        parent::__construct($model);
    }
}
