<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\GroupAssessment;
use App\Services\CourseService;
use App\Services\GroupService;
use App\Services\CourseStudentService;
use App\Services\GroupAssessmentService;
use App\Services\IntraGroupAssessmentService;
use App\Services\IntraGroupAssessmentUserMarkService;
use App\Services\GroupAssessmentUserMarkService;
use App\Services\GroupAssessmentUserResponseService;

class StudentApiController extends Controller
{
    public function getCourses(CourseStudentService $service)
    {
        $user = Auth::user();
        $courseStudents = $service->where([
            'user_id' => $user->id
        ], ['course']);
        return $courseStudents->pluck('course')->map(function($course){
            $course->available = $course->available;
            return $course;
        });
    }

    public function getCourseDetail($id, Request $request, CourseService $service)
    {
        $with = $request->query('with');
        $result = $service->where(['id' => $id], $with);
        if($result->count() > 0) {
            return $result->first();
        } else {
            return null;
        }
    }

    public function getGroups($courseId, GroupService $service)
    {
        return $service->where(['course_id' => $courseId], ['members.user']);
    }

    public function getGroupMembers($courseId, Request $request, CourseStudentService $service)
    {
        $relation = $this->getCourseStudent($courseId);
        return $service->where([ 'group_id' => $relation->group_id ], $request->query('with'));
    }

    public function getIntraGroupAssessment($id, IntraGroupAssessmentService $service)
    {
        $assessment = $service->where(['id' => $id], ['criterias'])->first();
        $assessment->available = $assessment->available;
        return $assessment;
    }

    private function getCourseStudentRelation($userId, $courseId)
    {
        $service = resolve(CourseStudentService::class);
        return $service->where([ 'user_id' => $userId, 'course_id' => $courseId ])->first();
    }

    public function getIntraGroupAssessmentMarks($id, IntraGroupAssessmentService $service, IntraGroupAssessmentUserMarkService $markService)
    {
        $assessment = $service->where(['id' => $id], ['course'])->first();
        $relation = $this->getCourseStudent($assessment->course->id);
        return $markService->where([
            'from_id' => $relation->id,
            'asg_id' => $id
        ]);
    }

    public function submitIntraGroupAssessmentMarks($id, IntraGroupAssessmentService $service, IntraGroupAssessmentUserMarkService $markService, Request $request)
    {
        $assessment = $service->where(['id' => $id], ['course'])->first();
        $relation = $this->getCourseStudent($assessment->course->id);
        $marks = collect($request->input('marks'))->map(function($mark) use ($relation, $markService) {
            $mark['from_id'] = $relation->id;
            return $mark;
        });
        return $markService->upsert($marks);
    }

    public function getGroupAssessment(GroupAssessment $assessment)
    {
        $assessment->available = $assessment->available;
        return $assessment;
    }

    public function getGroupAssessmentMarks($id, GroupAssessmentService $asgService, GroupAssessmentUserMarkService $markService)
    {
        $assessment = $asgService->where(['id' => $id], ['course'])->first();
        $relation = $this->getCourseStudent($assessment->course->id);
        return $markService->where(['from_id' => $relation->id, 'asg_id' => $assessment->id]);
    }

    public function getGroupAssessmentResponses($id, GroupAssessmentService $asgService, GroupAssessmentUserResponseService $responseService)
    {
        $assessment = $asgService->where(['id' => $id], ['course'])->first();
        $relation = $this->getCourseStudent($assessment->course->id);
        return $responseService->where(['from_id' => $relation->id, 'asg_id' => $assessment->id]);
    }

    public function getCourseStudent($courseId)
    {
        $user = Auth::user();
        return $this->getCourseStudentRelation($user->id, $courseId);
    }

    public function submitInterGroupAssessmentMarks($id, Request $request, GroupAssessmentUserMarkService $service)
    {
        return $service->upsert($request->input('marks'));
    }

    public function submitInterGroupAssessmentResponses($asgId, Request $request, GroupAssessmentUserResponseService $service)
    {
        return $service->upsert($asgId, $request->input('responses'));
    }

    public function getAnsweredGroupAssessments($courseId, GroupAssessmentService $service, GroupAssessmentUserMarkService $assessmentService)
    {
        $assessments = $service->where(['course_id' => $courseId]);
        $relation = $this->getCourseStudent($courseId);
        return $assessmentService->where([
            'asg_id' => $assessments->pluck('id')->values(),
            'from_id' => $relation->id
        ])->pluck('asg_id')->unique();
    }

    public function getAnsweredIntraGroupAssessments($courseId, IntraGroupAssessmentService $service, IntraGroupAssessmentUserMarkService $assessmentService)
    {
        $assessments = $service->where(['course_id' => $courseId]);
        $relation = $this->getCourseStudent($courseId);
        return $assessmentService->where([
            'asg_id' => $assessments->pluck('id')->values(),
            'from_id' => $relation->id
        ])->pluck('asg_id')->unique();
    }
}
