<?php

namespace Database\Seeders;

use App\Models\EntradasMaderaMaderas;
use Illuminate\Database\Seeder;

class EntradasMaderaMaderasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EntradasMaderaMaderas::factory()->count(50)->create();
    }
}
