<?php

use App\Http\Controllers\CostosInfraestructuraController;
use App\Http\Controllers\CostosOperacionController;
use App\Http\Controllers\DescripcionController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\OperacionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\MaderaController;
use App\Http\Controllers\EntradaMaderaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/','home')->name('inicio');

Route::view('costos-maquina','modulos.maquinas')->name('maquinas')->middleware('auth');

Route::get('/costos-maquina', [MaquinaController::class, 'index'])->name('maquinas.index')->middleware('auth');
Route::post('/costos-maquina', [MaquinaController::class,'store'] )->name('maquinas.store')->middleware('auth');
Route::delete('/costos-maquina/{maquina}', [MaquinaController::class,'destroy'])->name('maquinas.destroy')->middleware('auth');
Route::get('/costos-maquina/{maquina}/edit', [MaquinaController::class,'edit'])->name('maquinas.edit')->middleware('auth');
Route::patch('/costos-maquina/{maquina}', [MaquinaController::class,'update'])->name('maquinas.update')->middleware('auth');

Route::get('/costos-operacion', [OperacionController::class, 'index'])->name('operaciones.index')->middleware('auth');
Route::post('/costos-operacion', [OperacionController::class,'store'] )->name('operaciones.store')->middleware('auth');
Route::delete('/costos-operacion/{operacion}', [OperacionController::class,'destroy'])->name('operaciones.destroy')->middleware('auth');
Route::get('/costos-operacion/{operacion}/edit', [OperacionController::class,'edit'])->name('operaciones.edit')->middleware('auth');
Route::patch('/costos-operacion/{operacion}', [OperacionController::class,'update'])->name('operaciones.update')->middleware('auth');


Route::get('/costos-descripcion', [DescripcionController::class, 'index'])->name('descripciones.index')->middleware('auth');
Route::post('/costos-descripcion', [DescripcionController::class,'store'] )->name('descripciones.store')->middleware('auth');
Route::delete('/costos-descripcion/{descripcion}', [DescripcionController::class,'destroy'])->name('descripciones.destroy')->middleware('auth');
Route::get('/costos-descripcion/{descripcion}/edit', [DescripcionController::class,'edit'])->name('descripciones.edit')->middleware('auth');
Route::patch('/costos-descripcion/{descripcion}', [DescripcionController::class,'update'])->name('descripciones.update')->middleware('auth');


Route::resource('costos-de-operacion', CostosOperacionController::class)
            ->parameters(['costos-de-operacion' => 'costos-operacion'])
            ->names('costos-de-operacion')
            ->middleware('auth');

Route::resource('costos-de-infraestructura', CostosInfraestructuraController::class)
            ->parameters(['costos-de-infraestructura' => 'costos-infraestructura'])
            ->names('costos-de-infraestructura')
            ->middleware('auth');

Route::post('descripciones', [CostosOperacionController::class, 'descripciones'])
        ->name('descripciones');

Route::resource('usuarios', UsuarioController::class)
            ->parameters(['usuarios' => 'usuario'])
            ->names('usuarios')
            ->middleware('auth');

Route::resource('proveedores', ProveedorController::class)
            ->parameters(['proveedores' => 'proveedor'])
            ->names('proveedores')
            ->middleware('auth');

Route::resource('roles',RolController::class)
            ->parameters(['roles' => 'rol'])
            ->names('roles')
            ->middleware('auth');


Route::resource('maderas', MaderaController::class)
            ->parameters(['maderas' => 'madera'])
            ->names('maderas')
            ->middleware('auth');

Route::resource('entradas-maderas', EntradaMaderaController::class)
            ->parameters(['entradas-maderas' => 'entrada'])
            ->names('entradas-maderas')
            ->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


