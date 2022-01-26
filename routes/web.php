<?php

use App\Http\Controllers\MaquinaController;
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

Route::view('/','home');

Route::view('costos-maquina','modulos.maquinas')->name('maquinas')->middleware('auth');

Route::get('/costos-maquina', [MaquinaController::class, 'index'])->name('maquinas.index')->middleware('auth');
Route::post('/costos-maquina', [MaquinaController::class,'store'] )->name('maquinas.store')->middleware('auth');
Route::delete('/costos-maquina/{maquina}', [MaquinaController::class,'destroy'])->name('maquinas.destroy')->middleware('auth');
Route::get('/costos-maquina/{maquina}/edit', [MaquinaController::class,'edit'])->name('maquinas.edit')->middleware('auth');
Route::patch('/costos-maquina/{maquina}', [MaquinaController::class,'update'])->name('maquinas.update')->middleware('auth');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
