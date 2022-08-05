<?php

namespace Database\Seeders;

use App\Models\TipoMadera;
use Illuminate\Database\Seeder;

class TiposMaderaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoMadera::factory(9)->create();
    }
}
