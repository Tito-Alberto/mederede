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
        Schema::table('casos', function (Blueprint $table) {
            $table->string('bilhete')->nullable()->unique()->after('user_id');
            $table->date('data_nascimento')->nullable()->after('bilhete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casos', function (Blueprint $table) {
            $table->dropColumn(['bilhete', 'data_nascimento']);
        });
    }
};
