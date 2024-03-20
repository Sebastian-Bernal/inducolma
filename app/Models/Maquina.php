<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    use HasFactory, CheckRelations;

    protected $fillable = ['id','maquina','corte'];
    public function costos_operacion()
    {
        return $this->hasMany(CostosOperacion::class);
    }

    public function costos_infraestructura()
    {
        return $this->hasOne(CostosInfraestructura::class);
    }

    /**
     * relacion belongsToMany turnos
     */
    public function turnos()
    {
        return $this->belongsToMany(Turno::class, 'turno_usuarios');
    }

    /**
     * relacion belongsToMany usuarios
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'turno_usuarios');
    }

}
