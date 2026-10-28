<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('tipos_matricula', 'tipo_matricula');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('tipo_matricula', 'tipos_matricula');
    }
};
