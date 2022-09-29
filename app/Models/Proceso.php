<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    use HasFactory;

    /**
     * relacion belongsTo cubicaje
     */
    public function cubicaje()
    {
        return $this->belongsTo(Cubicaje::class);
    }

    /**
     * relacion belongsTo ordenProduccion
     */

    public function orden()
    {
        return $this->belongsTo(ordenProduccion::class);
    }
}
