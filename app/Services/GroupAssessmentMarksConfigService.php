<?php

namespace App\Services;

use App\Repositories\GroupAssessmentMarksConfigRepository;

class GroupAssessmentMarksConfigService extends BaseService
{
    public function __construct(GroupAssessmentMarksConfigRepository $repository)
    {
        parent::__construct($repository);
    }

    public function upsert($attributes)
    {
        return $this->repository->updateOrCreate([
            'asg_id' => $attributes['asg_id'],
        ], $attributes);
    }
}
