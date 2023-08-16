<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEvento extends Model
{
    use HasFactory, CheckRelations, SoftDeletes;

    /**
     * relacion tipo_eventos has many eventos

     */

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
