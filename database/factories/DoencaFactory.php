<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doenca>
 */
class DoencaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $doencas = [
            ['nome' => 'Malaria', 'codigo' => 'MAL-101'],
            ['nome' => 'Febre Amarela', 'codigo' => 'FAM-201'],
            ['nome' => 'Tuberculose', 'codigo' => 'TUB-301'],
            ['nome' => 'Sarampo', 'codigo' => 'SAR-401'],
        ];

        static $index = 0;
        $doenca = $doencas[$index % count($doencas)];
        $index++;

        return [
            'nome' => $doenca['nome'],
            'codigo' => $doenca['codigo'],
            'descricao' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['ativa', 'inativa']),
        ];
    }
}
