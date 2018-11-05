<?php

namespace App\Services;

use App\Repositories\GroupAssessmentTutorMarkRepository;

class GroupAssessmentTutorMarkService extends BaseService
{
    public function __construct(GroupAssessmentTutorMarkRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getOrNew($assessment)
    {
        $groups = $assessment->course->groups;
        return $groups->map(function ($group) use ($assessment) {
            return $this->repository->firstOrNew([
                'asg_id' => $assessment->id,
                'group_id' => $group->id
            ], ['marks' => 0]);
        });
    }

    public function upsert($marks)
    {
        return collect($marks)->map(function ($mark) {
            return $this->repository->updateOrCreate([
                'asg_id' => $mark['asg_id'],
                'group_id' => $mark['group_id']
            ], $mark);
        });
    }
}
