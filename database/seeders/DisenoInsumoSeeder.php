<?php

namespace Database\Seeders;

use App\Models\DisenoInsumo;
use App\Models\InsumosAlmacen;
use Illuminate\Database\Seeder;

class DisenoInsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DisenoInsumo::factory(50)->create();
    }
}
