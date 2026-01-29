<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuários de teste com diferentes roles
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mederede.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'ativo',
            'requested_role' => null,
            'bilhete' => '1234567890',
            'data_nascimento' => '1990-01-15',
        ]);

        

        // Criar 5 usuários públicos com bilhetes únicos
        for ($i = 1; $i <= 5; $i++) {
            \App\Models\User::factory()->create([
                'role' => 'publico',
                'status' => 'ativo',
                'requested_role' => null,
                'bilhete' => '999' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'data_nascimento' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            ]);
        }

        // Chamar os seeders específicos
        $this->call([
            DoencaSeeder::class,
            CasoSeeder::class,
        ]);
    }
}
