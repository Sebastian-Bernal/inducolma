<?php

namespace App\Http\Controllers;

use App\Models\SobranteTrozas;
use App\Repositories\SobranteTrozaRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SobranteTrozasController extends Controller
{

    private $sobranteTrozasRepository;

    public function __construct(SobranteTrozaRepository $sobranteTrozas)
    {
        $this->sobranteTrozasRepository = $sobranteTrozas;
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

        try {
            $this->sobranteTrozasRepository->procesarSobrantesTrozas($request->transformacionesSobrantes);
            return new Response(['message' => 'Guardado correctamente'], Response::HTTP_OK);
        } catch (Exception $e) {
            return new Response(['message' => 'Error al guardar: '. $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SobranteTrozas  $sobranteTrozas
     * @return \Illuminate\Http\Response
     */
    public function show(SobranteTrozas $sobranteTrozas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SobranteTrozas  $sobranteTrozas
     * @return \Illuminate\Http\Response
     */
    public function edit(SobranteTrozas $sobranteTrozas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SobranteTrozas  $sobranteTrozas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SobranteTrozas $sobranteTrozas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SobranteTrozas  $sobranteTrozas
     * @return \Illuminate\Http\Response
     */
    public function destroy(SobranteTrozas $sobranteTrozas)
    {
        //
    }
}
