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
        TipoEvento::firstOrCreate([

            'tipo_evento' => 'USUARIO',

        ]);

        TipoEvento::firstOrCreate([

            'tipo_evento' => 'MAQUINA',

        ]);
    }
}
