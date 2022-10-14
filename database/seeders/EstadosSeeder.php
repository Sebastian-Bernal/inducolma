<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::insert([
            'id' => 1 ,
            'descripcion' => 'ENCENDIDA',
            'user_id' => 1,
            'estado' => 'ENCENDIDA',
        ]);

        Estado::insert([
            'id' => 2 ,
            'descripcion' => 'APAGADA',
            'user_id' => 1,
            'estado' => 'APAGADA',
        ]);
    }
}
