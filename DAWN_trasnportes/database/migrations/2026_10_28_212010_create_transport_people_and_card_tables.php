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
        Schema::create('personas', function (Blueprint $table) {
            $table->string('dni', 32)->primary();
            $table->string('nombre', 45);
            $table->string('segundo_nombre', 45)->nullable();
            $table->string('apellidos', 45);
            $table->string('correo_electronico', 255)->unique();
            $table->string('contrasenia', 255);
            $table->string('num_telefono', 45);
            $table->enum('sexo', ['Masculino', 'Femenino']);
        });

        Schema::create('bancos', function (Blueprint $table) {
            $table->increments('id_banco');
            $table->string('nombre_banco', 45);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id_rol');
            $table->string('nombre_rol', 25);
        });

        Schema::create('tipos_matricula', function (Blueprint $table) {
            $table->increments('id_tipo');
            $table->string('tipo_matricula', 5);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_matricula');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('bancos');
        Schema::dropIfExists('personas');
    }
};
