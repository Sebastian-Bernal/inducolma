<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntradasMaderaMaderasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'entrada_madera_id' => $this->faker->numberBetween(2, 50),
            'madera_id' => $this->faker->numberBetween(2, 20),
            'm3entrada' => $this->faker->randomFloat(2, 1, 100),
            'condicion_madera' => $this->faker->randomElement(['BLOQUE', 'TROZA', 'regular']),
        ];
    }
}
