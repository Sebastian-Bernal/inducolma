<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rols';
    protected $fillable = [
        'nombre',
        'descripcion',
        'nivel',
    ];

    /**
     * relacion roles con usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
