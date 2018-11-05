<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseStudentService;
use App\Presenters\CourseStudentPresenter;

class ApiCoursesStudentsController extends Controller
{
    public function __construct(CourseStudentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CourseStudentPresenter $presenter)
    {
        $courseStudents = $this->service->where($request->query(), ['user', 'group']);
        return $courseStudents->map(function($courseStudent) use ($presenter){
            return $presenter->studentAsMain($courseStudent);
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->firstOrCreate($request->all());
    }

    public function batchStore(Request $request)
    {
        $courseId = $request->input('course_id');
        $studentsFile = $request->file('students_file');
        return $this->service->importFromCSV($courseId, $studentsFile)->values();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($id, $request->all());
        if($result) {
            return "success";
        } else {
            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
