<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cubicaje extends Model
{
    use HasFactory;

    /**
     * relacion hasMany procesos
     */
    public function procesos()
    {
        $this->hasMany(Proceso::class);
    }
    /**
     * relacion hasMany transformaciones
     */
    public function transformaciones()
    {
        $this->hasMany(Transformacion::class);
    }
}
