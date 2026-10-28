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
        Schema::create('viajes', function (Blueprint $table) {
            $table->increments('id_viaje');
            $table->unsignedInteger('id_ciudad_partida');
            $table->unsignedInteger('id_ciudad_llegada');
            $table->string('cliente_dni', 32);
            $table->unsignedInteger('vehiculo_id');
            $table->dateTime('fecha_partida');
            $table->dateTime('fecha_llegada');

            $table->foreign(['id_ciudad_partida', 'id_ciudad_llegada'])
                ->references(['id_ciudad_partida', 'id_ciudad_llegada'])
                ->on('ciudades_has_ciudades');
            $table->foreign('cliente_dni')->references('persona_dni')->on('clientes');
            $table->foreign('vehiculo_id')->references('id_vehiculo')->on('vehiculos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
