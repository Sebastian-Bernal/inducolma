<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMadera extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Relacion tipo_maderas hasMany maderas
     */

    public function maderas()
    {
        return $this->hasMany(Madera::class);
    }
}
