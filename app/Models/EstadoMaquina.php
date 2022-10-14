<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoMaquina extends Model
{
    use HasFactory;

    protected $table = 'estado_maquinas';
    protected $fillable = ['maquina_id','estado_id'];
}
