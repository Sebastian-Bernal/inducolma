<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoMaquina extends Model
{
    use HasFactory;
    protected $table = 'productos_maquina';

    protected $fillable = [
        'user_id',
        'maquina_id',
        'cantidad',
        'created_at',
    ];

}
