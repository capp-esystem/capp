<?php

namespace App\Repositories;

use App\GroupAssessmentUserResponse;

class GroupAssessmentUserResponseRepository extends BaseRepository
{
    public function __construct(GroupAssessmentUserResponse $model)
    {
        parent::__construct($model);
    }
}
