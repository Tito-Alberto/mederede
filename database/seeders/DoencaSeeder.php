<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoencaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('alertas')->truncate();
        DB::table('notificacaos')->truncate();
        DB::table('casos')->truncate();
        DB::table('doencas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $doencas = [
            ['nome' => 'Malaria', 'codigo' => 'MAL-101'],
            ['nome' => 'Febre Amarela', 'codigo' => 'FAM-201'],
            ['nome' => 'Tuberculose', 'codigo' => 'TUB-301'],
            ['nome' => 'Sarampo', 'codigo' => 'SAR-401'],
        ];

        foreach ($doencas as $doenca) {
            \App\Models\Doenca::create([
                'nome' => $doenca['nome'],
                'codigo' => $doenca['codigo'],
                'descricao' => null,
                'status' => 'ativa',
            ]);
        }
    }
}
