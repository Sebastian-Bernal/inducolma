<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntradaMaderaFactory extends Factory
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
            'fecha' => $this->faker->dateTimeBetween('-3 months ', 'now'),
            'acto_administrativo' => $this->faker->swiftBicNumber(),
            'salvoconducto_remision' => $this->faker->numberBetween(1,100000),
            'titular_salvoconducto' => $this->faker->name,
            'procedencia_madera' => $this->faker->city(),
            'entidad_vigilante' => $this->faker->company(),
            'estado' => $this->faker->randomElement(['PENDIENTE', 'ENTREGADO']),
            'proveedor_id' => $this->faker->numberBetween(5, 9),
            'user_id' => 6,
            'mes' => $this->faker->monthName($max = 'now'),
            'ano' => 2022,
            'hora' => $this->faker->time($format = 'H:i:s', $timezone = date_default_timezone_get()),

        ];
    }
}
