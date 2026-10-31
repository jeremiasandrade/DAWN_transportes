<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('referencia', 32)->unique();
            $table->string('viaje_codigo', 10);
            $table->string('origen', 100);
            $table->string('destino', 100);
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 32);
            $table->string('estado', 32)->default('pendiente');
            $table->string('nombre_pasajero', 100);
            $table->string('dni_pasajero', 32);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
