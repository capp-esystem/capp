<?php

namespace App\Services;

use Excel;
use App\Services\GroupAssessmentUserMarkService;
use App\Services\GroupAssessmentUserResponseService;
use App\Repositories\GroupAssessmentRepository;

class GroupAssessmentService extends BaseService
{
    public function __construct(GroupAssessmentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function exportRawData($id)
    {
        $marksService = resolve(GroupAssessmentUserMarkService::class);
        $responsesService = resolve(GroupAssessmentUserResponseService::class);
        return Excel::create("data", function ($excel) use ($id, $marksService, $responsesService) {
            $excel->sheet("marks", function ($sheet) use ($id, $marksService) {
                $sheet->rows($marksService->exportUserMarks($id));
            });
            $excel->sheet("responses", function ($sheet) use ($id, $responsesService) {
                $sheet->rows($responsesService->exportUserResponses($id));
            });
        });  
    }

    public function exportMarks($id, $marks)
    {
        return Excel::create("data", function ($excel) use ($id, $marks) {
            $excel->sheet("marks", function ($sheet) use ($id, $marks) {
                $sheet->appendRow(['Name', 'SID', 'Group', 'Total Marks', 'Tutor Marks', 'Moderated Marks', 'Final Peer Marks', 'Final Marks', 'Difference']);
                $marks->each(function($mark) use ($sheet) {
                    $sheet->appendRow([
                        $mark->student->user->name,
                        $mark->student->user->sid,
                        $mark->group->name,
                        $mark->total_marks,
                        $mark->tutor_marks,
                        $mark->moderated_marks,
                        $mark->final_peer_marks,
                        $mark->final_marks,
                        $mark->tutor_marks - $mark->final_marks
                    ]);
                });
            });
        });
    }
}
