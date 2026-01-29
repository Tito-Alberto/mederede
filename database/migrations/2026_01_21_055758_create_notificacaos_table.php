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
        Schema::create('notificacaos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('conteudo');
            $table->enum('tipo', ['prevencao', 'informacao', 'alerta'])->default('informacao');
            $table->date('data_publicacao');
            $table->enum('status', ['ativa', 'inativa', 'arquivada'])->default('ativa');
            $table->foreignId('doenca_id')->constrained('doencas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacaos');
    }
};
