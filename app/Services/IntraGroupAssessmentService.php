<?php

namespace App\Services;

use Excel;
use App\Services\IntraGroupAssessmentUserMarkService;
use App\Repositories\IntraGroupAssessmentRepository;

class IntraGroupAssessmentService extends BaseService
{
    public function __construct(IntraGroupAssessmentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function exportRawData($id)
    {
        $marksService = resolve(IntraGroupAssessmentUserMarkService::class);
        return Excel::create("data", function ($excel) use ($id, $marksService) {
            $excel->sheet("marks", function ($sheet) use ($id, $marksService) {
                $sheet->rows($marksService->exportUserMarks($id));
            });
        });  
    }

    public function exportMarks($id, $marks)
    {
        return Excel::create("data", function ($excel) use ($id, $marks) {
            $excel->sheet("marks", function ($sheet) use ($id, $marks) {
                $sheet->appendRow(['Name', 'SID', 'Raw Marks', 'PA Score', 'Adjusted PA Score', 'Final Marks', 'Adjusted Final Marks']);
                $marks->each(function($mark) use ($sheet) {
                    $sheet->appendRow([
                        $mark->student->user->name,
                        $mark->student->user->sid,
                        $mark->raw_marks,
                        $mark->pa_score,
                        $mark->adjusted_pa_score,
                        $mark->final_marks,
                        $mark->adjusted_final_marks
                    ]);
                });
            });
        });
    }
}
