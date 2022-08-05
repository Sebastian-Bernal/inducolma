<?php

namespace Database\Seeders;

use App\Models\Cubicaje;
use Illuminate\Database\Seeder;

class CubicajeSedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cubicaje::factory(50)->create();
    }
}
