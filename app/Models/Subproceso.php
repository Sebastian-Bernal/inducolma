<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subproceso extends Model
{
    use HasFactory;

    /**
     * relacion belongs to proceso
     */

    public function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }

    /**
     * relacion belongs to maquina
     */

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }

    /**
     * relacion belongs to User
     */

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed()->select(['id', 'name']);
    }
}
