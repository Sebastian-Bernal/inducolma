<?php

namespace Database\Seeders;

use App\Models\TipoEvento;
use Illuminate\Database\Seeder;

class TipoEventosDefecto extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoEvento::insert([
            'id' => 1 ,
            'tipo_evento' => 'USUARIO',

        ]);

        TipoEvento::insert([
            'id' => 2 ,
            'tipo_evento' => 'MAQUINA',

        ]);
    }
}
