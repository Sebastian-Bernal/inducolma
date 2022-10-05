<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'turnos';

    protected $fillable = [
        'turno',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    /**
     * relacion belongstoMany users
     */

    public function users()
    {
        return $this->belongsToMany(User::class, 'turno_usuarios');
    }

    /**
     * relacion belongstoMany maquinas
     */

    public function maquinas()
    {
        return $this->belongsToMany(Maquina::class, 'turno_usuarios');
    }
}
