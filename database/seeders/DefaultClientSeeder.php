<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class DefaultClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::create([
            'nit' => '1234567899',
            'nombre' => 'INDUCOLMA',
            'direccion' => 'cali',
            'telefono' => '321654215',
            'email' => 'inducolma@mail.com',
            'id_usuario' => 1,
            'razon_social' => 'sas',
        ]);
    }
}
