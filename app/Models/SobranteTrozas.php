<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SobranteTrozas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ancho',
        'largo',
        'alto',
        'estado',
        'cubicaje_id',
        'madera_id',
    ];


    public function getTipoAttribute()
    {
        return 'SOBRANTE_TROZA';
    }

    /**
     * Get the cubicaje that owns the SobranteTrozas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cubicaje(): BelongsTo
    {
        return $this->belongsTo(Cubicaje::class);
    }
}
