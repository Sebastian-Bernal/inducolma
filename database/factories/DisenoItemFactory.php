<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DisenoItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'diseno_producto_final_id' => $this->faker->numberBetween(8, 50),
            'item_id' => $this->faker->numberBetween(2, 50),
            'cantidad' => $this->faker->numberBetween(1, 20),
        ];
    }
}
