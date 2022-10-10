<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Obtiene el nombre del rol haciendo join con la tabla roles.
     */
    // public function nombreRol()
    // {
    //     return $this->hasOne(Rol::class, 'nombre');
    // }

    /**
     * relacion users belongs to rol

     */

    public function roll()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     *
     * relacion users hasMany diseno_producto_finales
     */
    public function diseño_producto_finales()
    {
        return $this->hasMany(DiseñoProductoFinal::class, 'user_id');
    }

    /**
     * relacion belongsToMany turnos
     */
    public function turnos()
    {
        return $this->belongsToMany(Turno::class, 'turno_usuarios', 'user_id');
    }

    /**
     * relacion belongsToMany maquinas
     */
    public function maquinas()
    {
        return $this->belongsToMany(Maquina::class, 'turno_usuarios');
    }

    /**
     * relacion hasMany TurnoUsuario
     */

    public function turno_usuario()
    {
        return $this->hasMany(TurnoUsuario::class, 'turno_usuarios');
    }
}
