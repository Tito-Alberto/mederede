<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caso>
 */
class CasoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paciente_nome' => $this->faker->name(),
            'idade' => $this->faker->numberBetween(1, 100),
            'localizacao' => $this->faker->city(),
            'data_inicio' => $this->faker->dateTime(),
            'sintomas' => $this->faker->sentence(15),
            'latitude' => $this->faker->latitude(-33.9, -1.04),
            'longitude' => $this->faker->longitude(-81.83, 40.84),
            'status' => $this->faker->randomElement(['confirmado', 'suspeito', 'descartado']),
            'doenca_id' => \App\Models\Doenca::inRandomOrder()->first()->id ?? \App\Models\Doenca::factory(),
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory(),
        ];
    }
}
