<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Presenters\DateTimePresenter;
use App\Services\IntraGroupAssessmentService;
use App\Services\IntraGroupAssessmentMarksCalculateService;

class ApiIntraGroupAssessmentsController extends Controller
{
    public function __construct(IntraGroupAssessmentService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DateTimePresenter $presenter)
    {
        $result = $this->service->where($request->query(), ['marksConfig'], ['marks', 'criterias']);
        return $result->map(function ($assessment) use ($presenter) {
            return $presenter->consistFormat($assessment, ['start_at', 'end_at']);
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
    public function show($id, Request $request)
    {
        $with = $request->query('with');
        return $this->service->where(['id' => $id], $with)->first();
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
        if ($result) {
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

    public function downloadRawData($id)
    {
        return $this->service->exportRawData($id)->download('xlsx');
    }

    public function calculateMarks($id, Request $request, IntraGroupAssessmentMarksCalculateService $service)
    {
        $config = $request->input('config');
        return $service->calculate($id, $config);
    }

    public function downloadMarks($id, IntraGroupAssessmentMarksCalculateService $service)
    {
        $assessment = $this->service->find($id);
        $marks = $service->calculate($id, $assessment->marksConfig);
        return $this->service->exportMarks($id, $marks)->download('xlsx');
    }
}
