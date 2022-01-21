<?php

namespace App\Http\Controllers;

use App\Models\CostosOperacion;
use App\Http\Requests\StoreCostosOperacionRequest;
use App\Http\Requests\UpdateCostosOperacionRequest;

class CostosOperacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCostosOperacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCostosOperacionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function show(CostosOperacion $costosOperacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function edit(CostosOperacion $costosOperacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCostosOperacionRequest  $request
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCostosOperacionRequest $request, CostosOperacion $costosOperacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostosOperacion $costosOperacion)
    {
        //
    }
}
