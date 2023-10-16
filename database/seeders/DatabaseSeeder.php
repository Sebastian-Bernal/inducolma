<?php

namespace Database\Seeders;

use App\Models\Cubicaje;
use App\Models\DisenoProductoFinal;
use App\Models\EntradaMadera;
use App\Models\Item;
use App\Models\Madera;
use App\Models\Pedido;
use App\Models\TipoMadera;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        /* TipoMadera::factory(9)->create();
        Madera::factory(9)->create();
        Item::factory(50)->create();
        DisenoProductoFinal::factory(50)->create();
        EntradaMadera::factory(50)->create();
        Cubicaje::factory(50)->create();
        Pedido::factory(50)->create(); */


        $this->call(RolSeeder::class);
        $this->call(DefaultUserSeeder::class);
        $this->call(DefaultClientSeeder::class);
        $this->call(EstadosSeeder::class);
        $this->call(TipoEventosDefecto::class);





    }
}
