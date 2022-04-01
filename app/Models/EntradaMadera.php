<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaMadera extends Model
{
    use HasFactory;

    //relacion EntradaMadera hasOne Proveedor
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    //relacion EntradaMadera hasOne Madera
    public function madera(){
        return $this->belongsTo(Madera::class);
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
