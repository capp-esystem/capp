<?php

namespace App\Http\Controllers;

use App\GroupAssessment;
use Illuminate\Http\Request;
use App\Services\GroupAssessmentTutorMarkService;

class ApiGroupAssessmentTutorMarksController extends Controller
{
    public function __construct(GroupAssessmentTutorMarkService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GroupAssessment $assessment)
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
