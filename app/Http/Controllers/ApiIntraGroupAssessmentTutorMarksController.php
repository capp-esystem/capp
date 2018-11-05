<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IntraGroupAssessmentTutorMarkService;
use App\IntraGroupAssessment;

class ApiIntraGroupAssessmentTutorMarksController extends Controller
{
    public function __construct(IntraGroupAssessmentTutorMarkService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IntraGroupAssessment $assessment)
    {
        return $this->service->getOrNew($assessment);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->upsert($request->input('marks'));
    }
}
