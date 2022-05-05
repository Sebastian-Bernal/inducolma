<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $autorizado = auth()->user()->rol;
        switch ($autorizado) {
            case 1:
                return redirect()->route('entradas-maderas.index');
                break;
            case 2:
                return redirect()->route('cubicaje.index');
                break;
            default:
                return view('home');
                break;
        }
       
    }
}
