<?php

namespace App\Services;

use App\Repositories\IntraGroupAssessmentUserMarkRepository;

class IntraGroupAssessmentUserMarkService extends BaseService
{
    public function __construct(IntraGroupAssessmentUserMarkRepository $repository)
    {
        parent::__construct($repository);
    }

    public function upsert($marks)
    {
        return collect($marks)->map(function ($mark) {
            return $this->repository->updateOrCreate([
                'asg_id' => $mark['asg_id'],
                'from_id' => $mark['from_id'],
                'to_id' => $mark['to_id'],
                'criteria_id' => $mark['criteria_id']
            ], $mark);
        });
    }

    public function exportUserMarks($id)
    {
        $header = ["Group Name", "From Student Email", "From Student Name", "From Student SID", "To Student Email", "To Student Name", "To Student SID", "Criteria Name", "Marks"];
        $marks = $this->repository->where(['asg_id' => $id], ['fromUser.user', 'fromUser.group', 'toUser.user', 'criteria']);
        $rows = $marks->map(function ($mark) {
            return [
                $mark->fromUser->group->name,
                $mark->fromUser->user->email,
                $mark->fromUser->user->name,
                $mark->fromUser->user->sid,
                $mark->toUser->user->email,
                $mark->toUser->user->name,
                $mark->toUser->user->sid,
                $mark->criteria->name,
                $mark->marks
            ];
        })->all();
        return array_merge([$header], $rows);
    }
}
