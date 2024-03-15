<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntradaMadera extends Model
{
    use HasFactory, CheckRelations;
    protected $table = 'entrada_maderas';

    //relacion EntradaMadera belongsTo Proveedor
    public function proveedor(){
        // cargar los proveedores eliminados
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'id')->withTrashed();
        //return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    //relacion EntradaMadera  EntradasMaderaMaderas
    public function maderas(){
       // return $this->belongsToMany(Madera::class, 'entradas_madera_maderas');
        return $this->belongsToMany(Madera::class, 'entradas_madera_maderas')
                        ->as('maderas_entrada');
    }

    //relacion EntradaMadera hasMany EntradasMaderaMaderas
    public function entradas_madera_maderas(){
        return $this->hasMany(EntradasMaderaMaderas::class, 'entrada_madera_id');
    }

    //funcion para setear el nombre del acto administrativo
    public function setActoAdministrativoAttribute($value){
        $this->attributes['acto_administrativo'] = strtoupper($value);
    }

    //funcion para setear el nombre del salvoconducto
    public function setSalvoconductoRemisionAttribute($value){
        $this->attributes['salvoconducto_remision'] = strtoupper($value);
    }

    //funcion para setear el nombre del titular del salvoconducto
    public function setTitularSalvoconductoAttribute($value){
        $this->attributes['titular_salvoconducto'] = strtoupper($value);
    }

    //funcion para setear el nombre del entidad vigilante
    public function setEntidadVigilanteAttribute($value){
        $this->attributes['entidad_vigilante'] = strtoupper($value);
    }

    //funcion para setear el nombre del tipo de madera
    public function setTipoMaderaAttribute($value){
        $this->attributes['tipo_madera'] = strtoupper($value);
    }

    //funcion para setear el nombre de la condicion de la madera
    public function setCondicionMaderaAttribute($value){
        $this->attributes['condicion_madera'] = strtoupper($value);
    }

    //funcion para setear el nombre del vitacora
    public function setVitacoraAttribute($value){
        $this->attributes['vitacora'] = strtoupper($value);
    }

    /**
     * relacion entrada madera hasMany CalificacionMadera
     */
    public function calificacionesMadera(){
        return $this->hasMany(CalificacionMadera::class);
    }

    /**
     * Get all of the cubicajes for the EntradaMadera
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cubicajes(): HasMany
    {
        return $this->hasMany(Cubicaje::class, 'entrada_madera_id', 'id');
    }
}
