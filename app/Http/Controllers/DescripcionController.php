<?php

namespace App\Http\Controllers;

use App\Models\Descripcion;
use App\Http\Requests\StoreDescripcionRequest;
use App\Http\Requests\UpdateDescripcionRequest;

class DescripcionController extends Controller
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
     * @param  \App\Http\Requests\StoreDescripcionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDescripcionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function show(Descripcion $descripcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Descripcion $descripcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDescripcionRequest  $request
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDescripcionRequest $request, Descripcion $descripcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descripcion $descripcion)
    {
        //
    }
}
