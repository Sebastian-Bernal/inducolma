<?php

use App\Http\Controllers\CalificacionMaderaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CostosInfraestructuraController;
use App\Http\Controllers\CostosOperacionController;
use App\Http\Controllers\CubicajeController;
use App\Http\Controllers\DescripcionController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\OperacionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\MaderaController;
use App\Http\Controllers\EntradaMaderaController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InsumosAlmacenController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\TipoEventoController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\ContratistaController;
use App\Http\Controllers\DisenoProductoFinalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
use Illuminate\Support\Facades\DB;
DB::listen(function($query){
        var_dump($query->sql);
    });
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/','auth.login');

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

Route::post('revisa-acto', [EntradaMaderaController::class, 'verificarRegistro'])
        ->name('revisa-acto')
        ->middleware('auth');


Route::post('ultima-entrada', [EntradaMaderaController::class, 'ultimaEntrada'])
        ->name('ultima-entrada')
        ->middleware('auth');
        
Route::post('elimina-madera', [EntradaMaderaController::class, 'eliminarMadera'])
        ->name('elimina-madera')
        ->middleware('auth');


Route::resource('cubicaje',CubicajeController::class)
                ->parameters(['cubicaje'=> 'cubicaje'])
                ->names('cubicaje')
                ->middleware('auth');


Route::resource('clientes',ClienteController::class)
                ->parameters(['clientes'=> 'cliente'])
                ->names('clientes')
                ->middleware('auth');

Route::resource('eventos',EventoController::class)
                ->parameters(['eventos'=> 'evento'])
                ->names('eventos')
                ->middleware('auth');

Route::resource('estados',EstadoController::class)
                ->parameters(['estados'=> 'estado'])
                ->names('estados')
                ->middleware('auth');

Route::resource('insumos-almacen', InsumosAlmacenController::class)
                ->parameters(['insumos-almacen'=> 'insumo_almacen'])
                ->names('insumos-almacen')
                ->middleware('auth');

Route::resource('items', ItemController::class)
                ->parameters(['items'=> 'item'])
                ->names('items')
                ->middleware('auth');

Route::resource('pedidos', PedidoController::class)
                ->parameters(['pedidos'=> 'pedido'])
                ->names('pedidos')
                ->middleware('auth');

Route::resource('tipo-eventos', TipoEventoController::class)
                ->parameters(['tipo-eventos'=> 'tipo_evento'])
                ->names('tipo-eventos')
                ->middleware('auth');

Route::resource('recepcion', RecepcionController::class)
                ->parameters(['recepcion'=> 'recepcion'])
                ->names('recepcion')
                ->middleware('auth');

Route::post('recepcion-usuraio', [RecepcionController::class, 'consultaUsuario'])
        ->name('recepcion-usuraio')
        ->middleware('auth');

Route::controller(RecepcionController::class)->group(function () {
       // Route::post('recepcion-usuario','consultaUsuario')->name('recepcion-usuario')->middleware('auth');
        Route::get('recepcion-reporte','reporteRecepcion')->name('recepcion-reporte')->middleware('auth');
        Route::post('recepcion-consulta','reporteRecepcion')->name('recepcion-consulta')->middleware('auth');
        Route::post('recepcion-empleado','recepcionEmpleado')->name('recepcion-empleado')->middleware('auth');
        Route::post('recepcion-contratista','recepcionContratista')->name('recepcion-contratista')->middleware('auth');
});

Route::resource('calificaciones', CalificacionMaderaController::class)
                ->parameters(['calificaciones'=> 'calificacion'])
                ->names('calificaciones')
                ->middleware('auth');

Route::resource('contratistas', ContratistaController::class)
                ->parameters(['contratistas'=> 'contratista'])
                ->names('contratistas')
                ->middleware('auth');


Route::controller(PedidoController::class)->group(function () {
        Route::get('pedidos-cliente/{cliente}','consultaPedidoCliente')
                ->name('pedidos-cliente')
                ->middleware('auth');
});

Route::resource('disenos',DisenoProductoFinalController::class)
                ->parameters(['diseños'=> 'diseño'])
                ->names('disenos')
                ->middleware('auth');
                
Auth::routes([
            'register' => false,            

        ]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


