<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::firstOrCreate([

            'nombre' => 'Recepcionista madera',
            'descripcion' => 'Permite usar el módulo de recepción de maderas en portería, también permite la recepción de personal, contratistas y visitantes.',

        ]);
        Rol::firstOrCreate([

            'nombre' => 'Operario maquinas',
            'descripcion' => 'Permite el trabajo en máquinas o en cubicaje dependiendo de la programación a la que han asignado al usuario.',

        ]);
      /*   Rol::firstOrCreate([

            'nombre' => 'Auxiliar administrativo contable',
            'descripcion' => 'descripcion del rol es editable',

        ]); */

       /*  Rol::firstOrCreate([

            'nombre' => 'ventas',
            'descripcion' => 'descripcion del rol es editable',
        ]); */

        /* Rol::firstOrCreate([

            'nombre' => 'Maderas',
            'descripcion' => 'descripcion del rol es editable',
        ]); */

        Rol::firstOrCreate([

            'nombre' => 'Administracion',
            'descripcion' => 'Permite el manejo de toda la gestión administrativa y sus módulos.',
        ]);
        Rol::firstOrCreate([

            'nombre' => 'Gerencia',
            'descripcion' => 'Permite el manejo de toda la gestión administrativa y sus módulos.',
        ]);
    }
}
