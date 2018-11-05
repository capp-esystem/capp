<?php

namespace App\Presenters;

class CourseStudentPresenter
{
    public function studentAsMain($courseStudent)
    {
        $student = $courseStudent->user;
        $student->course_relation_id = $courseStudent->id;
        $student->course_id = $courseStudent->course_id;
        $student->group_id = $courseStudent->group_id;
        $student->group = $courseStudent->group;
        return $student;
    }
}
