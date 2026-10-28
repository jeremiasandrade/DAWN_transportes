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
        Schema::create('provincias', function (Blueprint $table) {
            $table->increments('id_provincia');
            $table->string('nombre_provincia', 45)->nullable();
        });

        Schema::create('ciudades', function (Blueprint $table) {
            $table->increments('id_ciudad');
            $table->string('nombre_ciudad', 45)->unique();
            $table->unsignedInteger('provincia_id');

            $table->foreign('provincia_id')->references('id_provincia')->on('provincias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudades');
        Schema::dropIfExists('provincias');
    }
};
