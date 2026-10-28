<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('the transport schema supports a trip and its related records', function () {
    $tableNames = [
        'provincias',
        'ciudades',
        'personas',
        'bancos',
        'tarjetas',
        'roles',
        'administradores',
        'choferes',
        'clientes',
        'tipo_matricula',
        'matriculas',
        'ciudades_has_ciudades',
        'vehiculos',
        'viajes',
    ];

    foreach ($tableNames as $tableName) {
        expect(Schema::hasTable($tableName))->toBeTrue();
    }

    DB::table('provincias')->insert(['nombre_provincia' => 'Misiones']);
    DB::table('ciudades')->insert([
        ['nombre_ciudad' => 'Leandro N. Alem', 'provincia_id' => 1],
        ['nombre_ciudad' => 'Posadas', 'provincia_id' => 1],
    ]);
    DB::table('personas')->insert([
        ['dni' => '12345678', 'nombre' => 'Ana', 'apellidos' => 'Pérez', 'correo_electronico' => 'ana@example.com', 'contrasenia' => 'hashed-password', 'num_telefono' => '555123456', 'sexo' => 'Femenino'],
        ['dni' => '87654321', 'nombre' => 'Luis', 'apellidos' => 'Gómez', 'correo_electronico' => 'luis@example.com', 'contrasenia' => 'hashed-password', 'num_telefono' => '555654321', 'sexo' => 'Masculino'],
    ]);
    DB::table('clientes')->insert(['persona_dni' => '12345678']);
    DB::table('choferes')->insert(['persona_dni' => '87654321']);
    DB::table('ciudades_has_ciudades')->insert([
        'id_ciudad_partida' => 1,
        'id_ciudad_llegada' => 2,
        'distancia' => 90,
    ]);
    DB::table('vehiculos')->insert([
        'modelo' => 'Bus',
        'marca' => 'Dawn',
        'patente' => 'ABC123',
        'chofer_dni' => '87654321',
    ]);
    DB::table('viajes')->insert([
        'id_ciudad_partida' => 1,
        'id_ciudad_llegada' => 2,
        'cliente_dni' => '12345678',
        'vehiculo_id' => 1,
        'fecha_partida' => '2026-11-01 08:00:00',
        'fecha_llegada' => '2026-11-01 10:00:00',
    ]);

    $this->assertDatabaseHas('viajes', [
        'id_viaje' => 1,
        'cliente_dni' => '12345678',
        'vehiculo_id' => 1,
    ]);
});
