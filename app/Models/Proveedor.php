<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'proveedores';

    //relacion proveedor hasMany EntradaMadera
    public function entradasMadera(){
        return $this->hasMany(EntradaMadera::class, 'proveedor_id', 'id');
    }
}
