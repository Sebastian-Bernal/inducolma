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
        DB::table('rols')->insert([
            'id' => 1 ,
            'nombre' => 'Recepcionista madera',
            'descripcion' => 'Permite usar el módulo de recepción de maderas en portería, también permite la recepción de personal, contratistas y visitantes.',

        ]);
        DB::table('rols')->insert([
            'id' => 2 ,
            'nombre' => 'Operario maquinas',
            'descripcion' => 'Permite el trabajo en máquinas o en cubicaje dependiendo de la programación a la que han asignado al usuario.',

        ]);
      /*   DB::table('rols')->insert([
            'id' => 3 ,
            'nombre' => 'Auxiliar administrativo contable',
            'descripcion' => 'descripcion del rol es editable',

        ]); */

       /*  DB::table('rols')->insert([
            'id' => 4 ,
            'nombre' => 'ventas',
            'descripcion' => 'descripcion del rol es editable',
        ]); */

        /* DB::table('rols')->insert([
            'id' => 5 ,
            'nombre' => 'Maderas',
            'descripcion' => 'descripcion del rol es editable',
        ]); */

        DB::table('rols')->insert([
            'id' => 6 ,
            'nombre' => 'Administracion',
            'descripcion' => 'Permite el manejo de toda la gestión administrativa y sus módulos.',
        ]);
        DB::table('rols')->insert([
            'id' => 7 ,
            'nombre' => 'Gerencia',
            'descripcion' => 'Permite el manejo de toda la gestión administrativa y sus módulos.',
        ]);
    }
}
