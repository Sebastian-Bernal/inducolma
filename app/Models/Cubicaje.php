<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cubicaje extends Model
{
    use HasFactory, CheckRelations;

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

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
