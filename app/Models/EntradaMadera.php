<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaMadera extends Model
{
    use HasFactory;
    protected $table = 'entrada_maderas';

    //relacion EntradaMadera hasOne Proveedor
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    //relacion EntradaMadera hasMany EntradasMaderaMaderas
    public function maderas(){
        return $this->belongsToMany(Madera::class, 'entradas_madera_maderas');
    }

    //relacion EntradaMadera hasMany EntradasMaderaMaderas
    public function entradas_madera_maderas(){
        return $this->hasMany(EntradasMaderaMaderas::class);
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


}
