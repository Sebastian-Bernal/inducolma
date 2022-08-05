<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DisenoProductoFinalFactory extends Factory
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
            'descripcion' => $this->faker->sentence,
            'estado' => $this->faker->randomElement(['EN USO', 'DISPONIBLE', 'NO DISPONIBLE']),
            'user_id' => 1,
            'tipo_madera_id' => $this->faker->numberBetween(2, 10),

        ];
    }
}
