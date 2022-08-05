<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
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
            'cantidad' => $this->faker->numberBetween(1, 1000),
            'fecha_solicitud' => $this->faker->dateTimeBetween('-1 months ', 'now'),
            'fecha_entrega' => $this->faker->dateTimeBetween('+1 months ', '+3 months'),
            'estado' => $this->faker->randomElement(['PENDIENTE', 'ENTREGADO']),
            'user_id' => 1,
            'cliente_id' => $this->faker->numberBetween(2, 5),
            'diseno_producto_final_id' => $this->faker->numberBetween(8, 50),
        ];
    }
}
