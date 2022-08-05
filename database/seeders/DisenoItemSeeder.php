<?php

namespace Database\Seeders;

use App\Models\DisenoItem;
use App\Models\Item;
use Illuminate\Database\Seeder;

class DisenoItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DisenoItem::factory(50)->create();
    }
}
