<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            "name" => "Admin Root",
            "email" => "admin@inducolma.com",
            "password" => bcrypt("admin123"),
            "rol_id" => 7,
            "identificacion" => "usuario root",
            "primer_nombre" => "Root",
            "segundo_nombre" => "Root",
            "primer_apellido" => "Root",
            "segundo_apellido" => "Root",

        ]);

    }
}
