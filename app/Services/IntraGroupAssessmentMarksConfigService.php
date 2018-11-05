<?php

namespace App\Services;

use App\Repositories\IntraGroupAssessmentMarksConfigRepository;

class IntraGroupAssessmentMarksConfigService extends BaseService
{
    public function __construct(IntraGroupAssessmentMarksConfigRepository $repository)
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
