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
        Schema::create('administradores', function (Blueprint $table) {
            $table->string('persona_dni', 32)->primary();
            $table->unsignedInteger('rol_id');

            $table->foreign('persona_dni')->references('dni')->on('personas');
            $table->foreign('rol_id')->references('id_rol')->on('roles');
        });

        Schema::create('choferes', function (Blueprint $table) {
            $table->string('persona_dni', 32)->primary();

            $table->foreign('persona_dni')->references('dni')->on('personas');
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->string('persona_dni', 32)->primary();

            $table->foreign('persona_dni')->references('dni')->on('personas');
        });

        Schema::create('matriculas', function (Blueprint $table) {
            $table->increments('id_matricula');
            $table->unsignedInteger('numero_matricula');
            $table->date('fecha_inicio');
            $table->date('fecha_caducidad');
            $table->enum('vigencia', ['Vigente', 'No vigente']);
            $table->unsignedInteger('tipo_matricula_id');
            $table->string('chofer_dni', 32);

            $table->foreign('tipo_matricula_id')->references('id_tipo')->on('tipos_matricula');
            $table->foreign('chofer_dni')->references('persona_dni')->on('choferes');
        });

        Schema::create('tarjetas', function (Blueprint $table) {
            $table->increments('id_tarjeta');
            $table->char('numero_tarjeta', 4)->comment('Solo los ultimos cuatro digitos; no almacenar el numero completo.');
            $table->enum('tipo_tarjeta', ['Credito', 'Debito']);
            $table->unsignedInteger('banco_id');
            $table->string('persona_dni', 32);

            $table->foreign('banco_id')->references('id_banco')->on('bancos');
            $table->foreign('persona_dni')->references('dni')->on('personas');
        });

        Schema::create('ciudades_has_ciudades', function (Blueprint $table) {
            $table->unsignedInteger('id_ciudad_partida');
            $table->unsignedInteger('id_ciudad_llegada');
            $table->unsignedInteger('distancia');
            $table->primary(['id_ciudad_partida', 'id_ciudad_llegada']);

            $table->foreign('id_ciudad_partida')->references('id_ciudad')->on('ciudades');
            $table->foreign('id_ciudad_llegada')->references('id_ciudad')->on('ciudades');
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->increments('id_vehiculo');
            $table->string('modelo', 45);
            $table->string('marca', 45);
            $table->string('patente', 45);
            $table->string('chofer_dni', 32);

            $table->foreign('chofer_dni')->references('persona_dni')->on('choferes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('ciudades_has_ciudades');
        Schema::dropIfExists('tarjetas');
        Schema::dropIfExists('matriculas');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('choferes');
        Schema::dropIfExists('administradores');
    }
};
