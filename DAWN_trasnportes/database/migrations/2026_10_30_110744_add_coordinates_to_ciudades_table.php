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
        Schema::table('provincias', function (Blueprint $table) {
            $table->string('nombre_provincia', 100)->nullable()->change();
        });

        Schema::table('ciudades', function (Blueprint $table) {
            $table->string('nombre_ciudad', 100)->change();
            $table->string('departamento', 100)->nullable()->after('nombre_ciudad');
            $table->decimal('latitud', 10, 7)->nullable()->after('provincia_id');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
        });

        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropUnique('ciudades_nombre_ciudad_unique');
            $table->unique(
                ['nombre_ciudad', 'provincia_id', 'departamento'],
                'ciudades_nombre_provincia_departamento_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropUnique('ciudades_nombre_provincia_departamento_unique');
            $table->unique('nombre_ciudad', 'ciudades_nombre_ciudad_unique');
            $table->dropColumn(['departamento', 'latitud', 'longitud']);
            $table->string('nombre_ciudad', 45)->change();
        });

        Schema::table('provincias', function (Blueprint $table) {
            $table->string('nombre_provincia', 45)->nullable()->change();
        });
    }
};
