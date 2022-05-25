<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisenoProductoFinal extends Model
{
    use HasFactory;
    protected $table = 'diseno_producto_finales';


    /**
     * relacion diseño_producto_finales hasOne maderas
     */
    public function madera()
    {
        return $this->belongsTo(Madera::class);
    }

    /**
     * relacion diseño_producto_finales belongsTo user
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

