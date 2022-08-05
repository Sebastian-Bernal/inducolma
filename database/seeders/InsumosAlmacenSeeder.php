<?php

namespace Database\Seeders;

use App\Models\InsumosAlmacen;
use Illuminate\Database\Seeder;

class InsumosAlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsumosAlmacen::factory(50)->create();
    }
}
