<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoUsuario extends Model
{
    use HasFactory;

    protected $table = 'turno_usuarios';

    /**
     * relacion belongsTo user
     */

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * relacion belongsTo maquina
     */

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }

    /**
     * relacion belongsTo Turno
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
}
