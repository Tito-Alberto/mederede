<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['pendente', 'ativo', 'rejeitado'])->default('pendente')->after('role');
            $table->enum('requested_role', ['publico', 'profissional_saude'])->nullable()->after('status');
        });

        DB::table('users')->update(['status' => 'ativo']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'requested_role']);
        });
    }
};
