<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('tipo', ['PDF', 'CSV'])->default('PDF');
            $table->enum('formato_analise', ['temporal', 'geografico', 'estatistico'])->default('estatistico');
            $table->dateTime('data_geracao');
            $table->text('filtros')->nullable();
            $table->string('caminho_arquivo')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios');
    }
};
