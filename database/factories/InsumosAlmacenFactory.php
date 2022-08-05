<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InsumosAlmacenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'descripcion' => $this->faker->sentence,
            'cantidad' => $this->faker->numberBetween(1, 100),
            'precio_unitario' => $this->faker->randomFloat(2, 1, 10),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'user_id' => 1,
        ];
    }
}
