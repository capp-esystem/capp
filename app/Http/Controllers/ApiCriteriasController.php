<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CriteriaService;

class ApiCriteriasController extends Controller
{
    public function __construct(CriteriaService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->service->where($request->query());
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

    public function batchStore(Request $request)
    {
        return collect($request->input('list'))->map(function ($item) {
            return $this->service->create($item);
        });
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

    public function batchDestroy(Request $request)
    {
        return collect($request->input('list'))->map(function ($id) {
            return $this->service->destroy($id);
        });
    }

    public function batchUpdate(Request $request)
    {
        $result = collect($request->input('list'))->map(function ($item) {
            $updateResult = $this->service->update($item['id'], $item);
            return collect([
                'id' => $item['id'],
                'result' => $updateResult
            ]);
        });
        $failResult = $result->filter(function ($value) {
            return !$value['result'];
        });
        if ($failResult->count() > 0) {
            abort(500, $failResult->pluck('id'));
        } else {
            return 'success';
        }
    }
}
