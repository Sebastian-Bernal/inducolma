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
            'descripcion' => 'descripcion del rol es editable',
            'nivel'  => 1,
        ]);
        DB::table('rols')->insert([
            'id' => 2 ,
            'nombre' => 'Operario maquinas',
            'descripcion' => 'descripcion del rol es editable',
            'nivel' => 2,
        ]);
        DB::table('rols')->insert([
            'id' => 3 ,
            'nombre' => 'Auxiliar administrativo contable',
            'descripcion' => 'descripcion del rol es editable',
            'nivel' => 3,
        ]);

        DB::table('rols')->insert([
            'id' => 4 ,
            'nombre' => 'ventas',
            'descripcion' => 'descripcion del rol es editable',
        ]);
        DB::table('rols')->insert([
            'id' => 5 ,
            'nombre' => 'Maderas',
            'descripcion' => 'descripcion del rol es editable',
        ]);
        DB::table('rols')->insert([
            'id' => 6 ,
            'nombre' => 'Administracion',
            'descripcion' => 'descripcion del rol es editable',
        ]);
        DB::table('rols')->insert([
            'id' => 7 ,
            'nombre' => 'Gerencia',
            'descripcion' => 'descripcion del rol es editable',
        ]);
    }
}
