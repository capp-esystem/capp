<?php

namespace App\Services;

use App\Services\IntraGroupAssessmentService;
use App\Services\CourseStudentService;
use Yish\Generators\Foundation\Service\Service;

class IntraGroupAssessmentMarksCalculateService extends Service
{
    public function calculate($id, $config)
    {
        $asgService = resolve(IntraGroupAssessmentService::class);
        $courseStudentService = resolve(CourseStudentService::class);

        $assessment = $asgService->find($id);
        $students = $courseStudentService->where(['course_id' => $assessment->course_id], ['user', 'group'])->sortBy('group_id')->values();

        $rawMarks = $students->map(function ($student) use ($students, $assessment) {
            $rawMarks = $this->calculateRawMarks($student, $students, $assessment->user_marks, $assessment->criterias->count());
            return (object)[
                'student' => $student,
                'raw_marks' => $rawMarks,
            ];
        });

        $paScores = $rawMarks->map(function ($relation) use ($rawMarks) {
            $paScore = $this->calculatePAScore($relation, $rawMarks);
            $relation->pa_score = $paScore;
            return $relation;
        });

        $adjustedPAScores = $paScores->map(function ($relation) use ($config) {
            $adjustedPAScore = $this->adjustPAScore($relation, $config);
            $relation->adjusted_pa_score = $adjustedPAScore;
            return $relation;
        });

        $finalMarks = $adjustedPAScores->map(function ($relation) use ($assessment, $config) {
            $finalMarks = $this->calculateFinalMarks($relation, $assessment->marks, $config);
            $relation->final_marks = $finalMarks;
            return $relation;
        });

        $adjustedFinalMarks = $finalMarks->map(function ($relation) use ($assessment, $config) {
            $adjustedFinalMarks = $this->adjustFinalMarks($relation, $assessment->user_marks, $config);
            $relation->adjusted_final_marks = $adjustedFinalMarks;
            return $relation;
        });

        return $adjustedFinalMarks;
    }

    private function calculateRawMarks($student, $students, $marks, $criteriasCount)
    {
        $totalMarksReceived = $marks->where('to_id', $student->id)->sum('marks');
        $totalNoOfMembersInGroup = $students->where('group_id', $student->group_id)->count();
        $maxMarksPossible = $criteriasCount * 3;
        return $totalMarksReceived / $totalNoOfMembersInGroup / $maxMarksPossible * 100;
    }

    private function calculatePAScore($currentRelation, $rawMarkRelations)
    {
        $student = $currentRelation->student;
        $rawMarks = $currentRelation->raw_marks;
        $averageRawMarksInGroup = $rawMarkRelations->filter(function ($relation) use ($student) {
            return $relation->student->group_id == $student->group_id;
        })->avg('raw_marks');
        if($averageRawMarksInGroup == 0) {
            return 0;
        }
        return $rawMarks / $averageRawMarksInGroup;
    }

    private function adjustPAScore($currentRelation, $config)
    {
        $paScore = $currentRelation->pa_score;
        switch ($config['pa_score_adjust_method']) {
            case 'percentage':
                return $paScore * $config['pa_score_adjust_value'] / 100;
            case 'power':
                return pow($paScore, $config['pa_score_adjust_value']);
            default:
                return $paScore;
        }
    }

    private function calculateFinalMarks($currentRelation, $tutorMarks, $config)
    {
        $paScore = $currentRelation->adjusted_pa_score;
        $groupId = $currentRelation->student->group_id;
        $tutorMark = $tutorMarks->first(function($mark) use ($groupId) {
            return $mark->group_id == $groupId;
        })->marks;
        return $paScore * $tutorMark;
    }

    private function adjustFinalMarks($currentRelation, $userMarks, $config)
    {
        $finalMarks = $currentRelation->final_marks;
        $hasPenalty = is_null($userMarks->first(function($mark) use ($currentRelation){
            return $mark->from_id == $currentRelation->student->id;
        }));
        $finalMarks = $hasPenalty ? $finalMarks * (1 - ($config['penalty'] / 100)) : $finalMarks;
        return min($finalMarks, $config['max_cap']);
    }
}
