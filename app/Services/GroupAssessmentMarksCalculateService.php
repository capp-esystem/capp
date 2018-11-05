<?php

namespace App\Services;

use Yish\Generators\Foundation\Service\Service;
use App\Services\CourseStudentService;
use App\Services\GroupAssessmentService;

class GroupAssessmentMarksCalculateService extends Service
{
    public function calculate($id, $config)
    {
        $asgService = resolve(GroupAssessmentService::class);
        $courseStudentService = resolve(CourseStudentService::class);
        $groupService = resolve(GroupService::class);
        
        $assessment = $asgService->find($id);
        $groups = $groupService->getBy('course_id', $assessment->course_id);
        $students = $courseStudentService->where(['course_id' => $assessment->course_id], ['user']);
        
        $marks = $groups->map(function ($group) use ($assessment) {
            $totalMarks = $assessment->userMarks->where('group_id', $group->id)->sum('marks');
            $tutorMarks = $assessment->marks->where('group_id', $group->id)->first()->marks;
            return (object)[
                'group' => $group,
                'total_marks' => $totalMarks,
                'tutor_marks' => $tutorMarks
            ];
        });

        $rawPeerScores = $marks->map(function ($relation) use ($students, $groups) {
            $relation->raw_peer_score = $this->calculateRawPeerScore($relation, $students, $groups);
            return $relation;
        });

        $moderatedMarks = $rawPeerScores->map(function ($relation) use ($config, $rawPeerScores) {
            $relation->moderated_marks = $this->moderateMarks($relation, $config, $rawPeerScores);
            return $relation;
        });

        return $students->map(function($student) use ($moderatedMarks, $assessment, $config) {
            $marksObtained = clone $moderatedMarks->first(function($mark) use ($student) {
                return $student->group_id == $mark->group->id;
            });
            $finalPeerMarks = $this->calculateFinalPeerMarks($student, $marksObtained, $assessment->userMarks, $config);
            $finalMarks = $this->calculateFinalMarks($student, $finalPeerMarks, $marksObtained, $assessment);
            $marksObtained->final_peer_marks = $finalPeerMarks;
            $marksObtained->final_marks = $finalMarks;
            $marksObtained->student = $student;
            return $marksObtained;
        });
    }

    private function calculateRawPeerScore($relation, $students, $groups)
    {
        $assessorsCount = $students->count() - $students->where('group_id', $relation->group->id)->count();
        $groupsCount = $groups->count();
        return $relation->total_marks / $assessorsCount / ($groupsCount - 1);
    }

    private function moderateMarks($relation, $config, $rawPeerScores)
    {
        $highestRawPeerScore = $rawPeerScores->max('raw_peer_score');
        $lowestRawPeerScore = $rawPeerScores->min('raw_peer_score');
        
        if ($config['moderate_method'] == 'high_only') {
            return $config['upper_limit'] * $relation->raw_peer_score / $highestRawPeerScore;
        } else {
            $rangeOfLimits = $config['upper_limit'] - $config['lower_limit'];
            $rangeOfRawPeerScore = $highestRawPeerScore - $lowestRawPeerScore;
            if($rangeOfRawPeerScore == 0) {
                return $config['lower_limit'];
            }
            return $rangeOfLimits * ($relation->raw_peer_score - $lowestRawPeerScore) / $rangeOfRawPeerScore + $config['lower_limit'];
        }
    }

    private function calculateFinalPeerMarks($student, $marksObtained, $userMarks, $config)
    {
        $finalMarks = $marksObtained->moderated_marks;
        $hasPenalty = is_null($userMarks->first(function($mark) use ($student) {
            return $student->id == $mark->from_id;
        }));
        if($hasPenalty) {
            return $finalMarks * (1 - ($config['penalty'] / 100));
        } else {
            return $finalMarks;
        }
    }

    private function calculateFinalMarks($student, $finalPeerMarks, $marksObtained, $assessment)
    {
        $tutorMarks = $marksObtained->tutor_marks;
        $peerContributionPercentage = $assessment->peer_contribution_percentage;
        return ($finalPeerMarks * $peerContributionPercentage / 100) + ($tutorMarks * (100 - $peerContributionPercentage) / 100);
    }
}
