<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubprocesoReuquest;
use App\Models\Subproceso;
use App\Repositories\guardarSubproceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubprocesoController extends Controller
{
    protected $guardarSubproceso;

    public function __construct( guardarSubproceso $guardarSubproceso)
    {
        $this->guardarSubproceso = $guardarSubproceso;
    }



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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $subproceso_existente = Subproceso::where('proceso_id', $request->procesoId)
                                ->latest()
                                ->first();
        return $this->guardarSubproceso->guardar($subproceso_existente, $request);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subproceso  $subproceso
     * @return \Illuminate\Http\Response
     */
    public function show(Subproceso $subproceso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subproceso  $subproceso
     * @return \Illuminate\Http\Response
     */
    public function edit(Subproceso $subproceso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subproceso  $subproceso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subproceso $subproceso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subproceso  $subproceso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subproceso $subproceso)
    {
        //
    }
}
