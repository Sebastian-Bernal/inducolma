<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use HasFactory, SoftDeletes ;
    
    /**
     * relacion eventos belogs to tipo_eventos
     */

    public function tipo_evento()
    {
        return $this->belongsTo(TipoEvento::class);
    }
}
