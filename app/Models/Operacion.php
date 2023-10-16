<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    protected $table = 'operaciones';
    use HasFactory, CheckRelations;

    public function descripciones()
    {
        return $this->hasMany(Descripcion::class);
    }


}
