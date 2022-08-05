<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
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
            'alto' => $this->faker->randomFloat(2, 1, 10),
            'ancho' => $this->faker->randomFloat(2, 1, 10),
            'largo' => $this->faker->randomFloat(2, 1, 10),
            'codigo_cg' => $this->faker->numberBetween(1, 10000),
            'user_id' => 1,
            'madera_id' => $this->faker->numberBetween(2, 10),

        ];
    }
}
