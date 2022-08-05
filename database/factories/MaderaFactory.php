<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaderaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'id' => $this->faker->numberBetween(1, 10),
            'nombre_cientifico' => $this->faker->sentence,
            'densidad' => $this->faker->randomElement(['ALTA DENSIDAD', 'MEDIA DENSIDAD', 'BAJA DENSIDAD']),
            'tipo_madera_id' => $this->faker->numberBetween(2, 10),

        ];
    }
}
