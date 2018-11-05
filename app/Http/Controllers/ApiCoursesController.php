<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Presenters\DateTimePresenter;

class ApiCoursesController extends Controller
{
    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DateTimePresenter $presenter)
    {
        $courses = $this->service->all();
        return $courses->map(function($course) use ($presenter){
            return $presenter->consistFormat($course, ['start_at', 'end_at']);
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
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, DateTimePresenter $presenter)
    {
        $with = $request->query('with', []);
        $withCount = $request->query('withCount', []);
        $course = $this->service->where(['id' => $id], $with, $withCount)->first();
        return $presenter->consistFormat($course, ['start_at', 'end_at']);
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
