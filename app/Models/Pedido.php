<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    //relacion de pedido pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'id');
    }

    //relacion de pedido pertenece a un diseño
    public function nombre_diseno()
    {
        return $this->belongsTo(DisenoProductoFinal::class);
    }

    // funcion para obtener los dias restantes de entrega

    public function getDiasAttribute()
    {
        $actual = date_create(date('Y-m-d'));
    
        $entrega = date_create($this->fecha_entrega);
    
        if ($actual > $entrega) return 0;
        $dias = 0;
        while ($actual <= $entrega) {
    
            $day_week = $actual->format('w');
           
            if ($day_week > 0 && $day_week < 6) {
                $dias += 1;
            }
            date_add($actual, date_interval_create_from_date_string('1 day'));
        }
        return $dias -1;        
    }
    
}
