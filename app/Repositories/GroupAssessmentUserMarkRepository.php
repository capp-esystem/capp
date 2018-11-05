<?php

namespace App\Repositories;

use App\GroupAssessmentUserMark;

class GroupAssessmentUserMarkRepository extends BaseRepository
{
    public function __construct(GroupAssessmentUserMark $model)
    {
        parent::__construct($model);
    }
}
