<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * Get the EntradaMadera that owns the Cubicaje
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrada_madera_madera(): BelongsTo
    {
        return $this->belongsTo(EntradasMaderaMaderas::class, 'entrada_madera_id', 'id');
    }
}
