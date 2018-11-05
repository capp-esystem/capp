<?php

namespace App\Services;

use App\Repositories\GroupAssessmentUserMarkRepository;

class GroupAssessmentUserMarkService extends BaseService
{
    public function __construct(GroupAssessmentUserMarkRepository $repository)
    {
        parent::__construct($repository);
    }

    public function upsert($marks)
    {
        return collect($marks)->map(function ($mark) {
            return $this->repository->updateOrCreate([
                'asg_id' => $mark['asg_id'],
                'from_id' => $mark['from_id'],
                'group_id' => $mark['group_id']
            ], $mark);
        });
    }

    public function exportUserMarks($id)
    {
        $header = ["From Student Email", "From Student Name", "To Group Name", "Marks"];
        $marks = $this->repository->where(['asg_id' => $id], ['fromUser.user', 'group']);
        $rows = $marks->map(function ($mark) {
            return [
                $mark->fromUser->user->email,
                $mark->fromUser->user->name,
                $mark->group->name,
                $mark->marks
            ];
        })->all();
        return array_merge([$header], $rows);
    }
}
