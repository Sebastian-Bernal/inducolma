<?php

namespace App\Http\Controllers;

use App\Models\DisenoItem;
use Illuminate\Http\Request;

class DisenoItemController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->item['diseno_id'];
        $diseno_item = new DisenoItem();
        $diseno_item->diseno_producto_final_id = $request->item['diseno_id'];
        $diseno_item->item_id = $request->item['item_id'];
        $diseno_item->cantidad = $request->item['cantidad'];
        
        if ($diseno_item->save()) {
            return response()->json(['success' => true, 'message' => 'Item agregado con éxito', 'itembd' => $diseno_item]);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al agregar el item']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DisenoItem  $disenoItem
     * @return \Illuminate\Http\Response
     */
    public function show(DisenoItem $disenoItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DisenoItem  $disenoItem
     * @return \Illuminate\Http\Response
     */
    public function edit(DisenoItem $disenoItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DisenoItem  $disenoItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DisenoItem $disenoItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DisenoItem  $disenoItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(DisenoItem $disenoItem)
    {
        if ($disenoItem->delete()) {
            return response()->json(['success' => true, 'message' => 'Item eliminado con éxito']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el item']);
        }

    }
}
