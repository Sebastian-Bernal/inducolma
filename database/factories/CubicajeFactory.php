<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CubicajeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'id' => $this->faker->numberBetween(1, 100),
            'paqueta' => $this->faker->numberBetween(1, 100),
            'largo' => $this->faker->randomFloat(2, 1, 100),
            'ancho' => $this->faker->randomFloat(2, 1, 100),
            'alto' => $this->faker->randomFloat(2, 1, 100),
            'cm3' => $this->faker->randomFloat(2, 1, 100),
            'pulgadas_cuadradas' => $this->faker->randomFloat(2, 1, 1000),
            'pulgadas_cuadradas_x3_metros' => $this->faker->randomFloat(2, 1, 1000),
            'bloque' => $this->faker->numberBetween(1, 100),
            'entrada_madera_id' => $this->faker->numberBetween(2, 10),
            'user_id' => 7,
        ];
    }
}
