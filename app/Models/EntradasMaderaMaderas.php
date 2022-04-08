<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradasMaderaMaderas extends Model
{
    use HasFactory;
    protected $table = 'entradas_madera_maderas';

    //relacion EntradasMaderaMaderas belongsTo EntradaMadera
    public function entrada_madera(){
        return $this->belongsTo(EntradaMadera::class );
    }

    //relacion EntradasMaderaMaderas belongsTo Madera
    public function madera(){
        return $this->belongsTo(Madera::class);
    }


}
