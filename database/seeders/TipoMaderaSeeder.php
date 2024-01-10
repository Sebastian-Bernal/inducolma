<?php

namespace Database\Seeders;

use App\Models\TipoMadera;
use Illuminate\Database\Seeder;

class TipoMaderaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoMadera::firstOrCreate([

            'descripcion' => 'TODOS',
            'user_id' => 1,

        ]);
    }
}
