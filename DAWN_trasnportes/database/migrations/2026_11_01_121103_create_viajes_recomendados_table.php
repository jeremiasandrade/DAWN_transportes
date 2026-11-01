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
        Schema::create('viajes_recomendados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('ruta')->nullable();
            $table->unsignedInteger('viaje_id')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->unsignedInteger('descuento_porcentaje')->default(0);
            $table->string('vehiculo')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->foreign('viaje_id')->references('id_viaje')->on('viajes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes_recomendados');
    }
};
