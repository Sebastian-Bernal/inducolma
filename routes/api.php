<?php

use App\Http\Controllers\RutaAcabadoProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(RutaAcabadoProductoController::class)->group(function(){
    Route::post('/crear-ruta-acabado-producto', 'store')->name('crear-ruta-acabado-producto');
    Route::put('/update-ruta-acabado-producto/{id}', 'update')->name('update-ruta-acabado-producto');
    Route::delete('/delete-ruta-acabado-producto/{id}', 'destroy')->name('delete-ruta-acabado-producto');
});
