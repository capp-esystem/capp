<?php

namespace App\Services;

use App\Repositories\GroupAssessmentUserResponseRepository;

class GroupAssessmentUserResponseService extends BaseService
{
    public function __construct(GroupAssessmentUserResponseRepository $repository)
    {
        parent::__construct($repository);
    }

    public function upsert($asgId, $responses)
    {
        $responsesCollection = collect($responses);
        $fromId = $responsesCollection->pluck('from_id')->unique()->first();
        $this->repository->deleteBy(['asg_id' => $asgId, 'from_id' => $fromId]);
        return $responsesCollection->map(function ($response) {
            return $this->repository->create($response);
        });
    }

    public function exportUserResponses($id)
    {
        $header = ["From Student Email", "From Student Name", "From Student SID", "To Group Name", "Content"];
        $responses = $this->repository->where(['asg_id' => $id], ['fromUser.user', 'group']);
        $rows = $responses->map(function ($response) {
            return [
                $response->fromUser->user->email,
                $response->fromUser->user->name,
                $response->fromUser->user->sid,
                $response->group->name,
                $response->content
            ];
        })->all();
        return array_merge([$header], $rows);
    }
}
