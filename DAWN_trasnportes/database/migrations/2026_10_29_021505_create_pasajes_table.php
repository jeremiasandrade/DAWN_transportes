<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('viaje_id');
            $table->string('persona_dni', 32);
            $table->unsignedInteger('numero_asiento');
            $table->decimal('precio', 10, 2)->default(0);
            $table->enum('estado', ['reservado', 'pagado', 'anulado', 'usado'])->default('reservado');
            $table->timestamps();

            $table->foreign('viaje_id')->references('id_viaje')->on('viajes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('persona_dni')->references('dni')->on('personas')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['viaje_id', 'numero_asiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasajes');
    }
};
