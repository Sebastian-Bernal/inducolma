<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InsumosAlmacen extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['cantidad', 'descripcion', 'user_id', 'precio_unitario', 'estado'];
    protected $table = 'insumos_almacen';


    /**
     * The diseno_insumos that belong to the InsumosAlmacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disenoInsumos(): BelongsToMany
    {
        return $this->belongsToMany(DisenoProductoFinal::class, 'diseno_insumos');
    }
}
