<?php

namespace App\Services;

use App\Mail\MarksEmail;
use Illuminate\Support\Facades\Mail;
use Yish\Generators\Foundation\Service\Service;

class EmailService extends Service
{
    public function sendEmails($courseId, $config)
    {
        $data = $this->prepareData($courseId, $config);
        $data->course->students->each(function ($student) use ($data) {
            $intraGroupAssessmentMarks = $data->intra_group_assessment_marks
            ->map(function ($assessmentMarks) use ($student) {
                $markForStudent = $assessmentMarks->marks->first(function ($mark) use ($student) {
                    return $mark->student->id == $student->id;
                })->adjusted_final_marks;
                return (object)[
                    'assessmentId' => $assessmentMarks->assessmentId,
                    'marks' => $markForStudent
                ];
            });
            $groupAssessmentMarks = $data->group_assessment_marks
            ->map(function ($assessmentMarks) use ($student) {
                $markForStudent = $assessmentMarks->marks->first(function ($mark) use ($student) {
                    return $mark->student->id == $student->id;
                })->final_marks;
                return (object)[
                    'assessmentId' => $assessmentMarks->assessmentId,
                    'marks' => $markForStudent
                ];
            });
            $groupAssessmentResponses = $data->group_assessment_responses
            ->map(function ($assessmentResponses) use ($student) {
                $responsesForStudents = $assessmentResponses->responses->filter(function ($response) use ($student) {
                    return $response->group_id == $student->group_id;
                });
                $groupedResponses = $responsesForStudents->groupBy(function($response){
                    return $response->fromUser->group_id;
                });
                return (object)[
                    'responses' => $groupedResponses,
                    'assessmentId' => $assessmentResponses->assessmentId
                ];
            });
            Mail::to($student->user)->queue(new MarksEmail((object)[
                "course" => $data->course,
                "student" => $student,
                "intraGroupAssessments" => $data->intra_group_assessments,
                'groupAssessments' => $data->group_assessments,
                'intraGroupAssessmentMarks' => $intraGroupAssessmentMarks,
                'groupAssessmentMarks' => $groupAssessmentMarks,
                'groupAssessmentResponses' => $groupAssessmentResponses
            ]));
        });
    }

    private function prepareData($courseId, $config)
    {
        $courseService = resolve(\App\Services\CourseService::class);
        $intraGroupAssessmentService = resolve(\App\Services\IntraGroupAssessmentService::class);
        $groupAssessmentService = resolve(\App\Services\GroupAssessmentService::class);
        $intraGroupAssessmentMarksCalculateService = resolve(\App\Services\IntraGroupAssessmentMarksCalculateService::class);
        $groupAssessmentMarksCalculateService = resolve(\App\Services\GroupAssessmentMarksCalculateService::class);
        $gpAssessmentResponsesService = resolve(\App\Services\GroupAssessmentUserResponseService::class);

        $course = $courseService->where(['id' => $courseId], ['students.user'])->first();
        $intraGroupAssessmentsToBeCollected = collect($config['intra_group_assessments'])->where('marked', true)->pluck('id');
        $groupAssessmentsToBeCollected = collect($config['group_assessments'])->where('marked', true)->pluck('id');
        $intraGroupAssessments = collect();
        $groupAssessments = collect();
        if ($intraGroupAssessmentsToBeCollected->count() > 0) {
            $intraGroupAssessments = $intraGroupAssessmentService->where(['id' => $intraGroupAssessmentsToBeCollected->values()], ['marksConfig']);
        }
        if ($groupAssessmentsToBeCollected->count() > 0) {
            $groupAssessments = $groupAssessmentService->where(['id' => $groupAssessmentsToBeCollected->values()], ['marksConfig', 'responses']);
        }
        $intraGroupAssessmentMarks = $intraGroupAssessments->map(function ($assessment) use ($intraGroupAssessmentMarksCalculateService) {
            return (object) [
                'assessmentId' => $assessment->id,
                'marks' => $intraGroupAssessmentMarksCalculateService->calculate($assessment->id, $assessment->marksConfig)
            ];
        });
        $gpAssessmentMarks = $groupAssessments->map(function ($assessment) use ($groupAssessmentMarksCalculateService) {
            return (object) [
                'assessmentId' => $assessment->id,
                'marks' => $groupAssessmentMarksCalculateService->calculate($assessment->id, $assessment->marksConfig)
            ];
        });
        $gpAssessmentResponses = $groupAssessments->map(function ($assessment) use ($gpAssessmentResponsesService) {
            return (object) [
                'assessmentId' => $assessment->id,
                'responses' => $gpAssessmentResponsesService->where(['asg_id' => $assessment->id], ['fromUser.user'])
            ];
        });
        return (object)[
            'course' => $course,
            'intra_group_assessments' => $intraGroupAssessments,
            'group_assessments' => $groupAssessments,
            'intra_group_assessment_marks' => $intraGroupAssessmentMarks,
            'group_assessment_marks' => $gpAssessmentMarks,
            'group_assessment_responses' => $gpAssessmentResponses
        ];
    }
}
