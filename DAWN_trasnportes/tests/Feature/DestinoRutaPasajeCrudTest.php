<?php

use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\Pasaje;
use App\Models\Persona;
use App\Models\Provincia;
use App\Models\Ruta;
use App\Models\Vehiculo;
use App\Models\Viaje;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function crudCrearCiudad(string $nombre, string $provincia = 'Misiones'): Ciudad
{
    $provinciaModelo = Provincia::firstOrCreate(['nombre_provincia' => $provincia]);

    return Ciudad::create([
        'nombre_ciudad' => $nombre,
        'provincia_id' => $provinciaModelo->id_provincia,
    ]);
}

function crudCrearPersona(string $dni, string $nombre, string $apellidos): Persona
{
    return Persona::create([
        'dni' => $dni,
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'correo_electronico' => strtolower($nombre).'@example.com',
        'contrasenia' => 'hashed-password',
        'num_telefono' => '555123456',
        'sexo' => 'Femenino',
    ]);
}

function crudCrearViaje(): Viaje
{
    $origen = crudCrearCiudad('Posadas');
    $destino = crudCrearCiudad('Corrientes', 'Corrientes');
    $cliente = crudCrearPersona('12345678', 'Ana', 'Pérez');
    $chofer = crudCrearPersona('87654321', 'Luis', 'Gómez');

    Cliente::create(['persona_dni' => $cliente->dni]);
    DB::table('choferes')->insert(['persona_dni' => $chofer->dni]);

    Ruta::create([
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $destino->id_ciudad,
        'distancia' => 300,
    ]);

    $vehiculo = Vehiculo::create([
        'modelo' => 'Sprinter',
        'marca' => 'Mercedes',
        'patente' => 'AB123CD',
        'chofer_dni' => $chofer->dni,
    ]);

    return Viaje::create([
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $destino->id_ciudad,
        'cliente_dni' => $cliente->dni,
        'vehiculo_id' => $vehiculo->id_vehiculo,
        'fecha_partida' => '2026-11-01 08:00:00',
        'fecha_llegada' => '2026-11-01 14:00:00',
    ]);
}

test('el crud de destinos administra ciudades y provincias', function () {
    $this->post(route('destinos.store'), [
        'nombre_ciudad' => 'Posadas',
        'provincia' => 'Misiones',
    ])->assertRedirect(route('destinos.index'));

    $ciudad = Ciudad::where('nombre_ciudad', 'Posadas')->first();

    expect($ciudad)->not->toBeNull();

    $this->get(route('destinos.index'))->assertOk()->assertSee('Posadas');
    $this->get(route('destinos.show', $ciudad))->assertOk()->assertSee('Misiones');

    $this->put(route('destinos.update', $ciudad), [
        'nombre_ciudad' => 'Posadas Capital',
        'provincia' => 'Corrientes',
    ])->assertRedirect(route('destinos.index'));

    $ciudad->refresh();

    expect($ciudad->nombre_ciudad)->toBe('Posadas Capital')
        ->and($ciudad->provincia->nombre_provincia)->toBe('Corrientes');

    $this->delete(route('destinos.destroy', $ciudad))->assertRedirect(route('destinos.index'));

    expect(Ciudad::find($ciudad->id_ciudad))->toBeNull();
});

test('el crud de rutas usa la clave compuesta de ciudades', function () {
    $origen = crudCrearCiudad('Posadas');
    $destino = crudCrearCiudad('Encarnación', 'Itapúa');

    $this->post(route('rutas.store'), [
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $destino->id_ciudad,
        'distancia' => 10,
    ])->assertRedirect(route('rutas.index'));

    $ruta = Ruta::first();

    expect($ruta)->not->toBeNull()
        ->and($ruta->getRouteKey())->toBe($origen->id_ciudad.'-'.$destino->id_ciudad);

    $this->get(route('rutas.show', $ruta))->assertOk()->assertSee('Posadas');

    $tercera = crudCrearCiudad('Corrientes', 'Corrientes');

    $this->put(route('rutas.update', $ruta), [
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $tercera->id_ciudad,
        'distancia' => 25,
    ])->assertRedirect(route('rutas.index'));

    $actualizada = Ruta::where('id_ciudad_partida', $origen->id_ciudad)
        ->where('id_ciudad_llegada', $tercera->id_ciudad)
        ->first();

    expect($actualizada)->not->toBeNull()
        ->and($actualizada->distancia)->toBe(25)
        ->and(Ruta::count())->toBe(1);

    $this->delete(route('rutas.destroy', $actualizada))->assertRedirect(route('rutas.index'));

    expect(Ruta::count())->toBe(0);
});

test('el crud de pasajes registra y actualiza pasajes por viaje', function () {
    $viaje = crudCrearViaje();
    $pasajero = crudCrearPersona('11223344', 'María', 'Sosa');

    $this->post(route('pasajes.store'), [
        'viaje_id' => $viaje->id_viaje,
        'persona_dni' => $pasajero->dni,
        'numero_asiento' => 5,
        'precio' => 1500.50,
        'estado' => 'reservado',
    ])->assertRedirect(route('pasajes.index'));

    $pasaje = Pasaje::first();

    expect($pasaje)->not->toBeNull()
        ->and($pasaje->numero_asiento)->toBe(5);

    $this->get(route('pasajes.index'))->assertOk()->assertSee('María');
    $this->get(route('pasajes.show', $pasaje))->assertOk()->assertSee('María');

    $this->put(route('pasajes.update', $pasaje), [
        'viaje_id' => $viaje->id_viaje,
        'persona_dni' => $pasajero->dni,
        'numero_asiento' => 5,
        'precio' => 1800,
        'estado' => 'pagado',
    ])->assertRedirect(route('pasajes.index'));

    expect($pasaje->fresh()->estado)->toBe('pagado');

    $this->delete(route('pasajes.destroy', $pasaje))->assertRedirect(route('pasajes.index'));

    expect(Pasaje::count())->toBe(0);
});

test('no se permite repetir el asiento en el mismo viaje', function () {
    $viaje = crudCrearViaje();
    $pasajero = crudCrearPersona('11223344', 'María', 'Sosa');

    Pasaje::create([
        'viaje_id' => $viaje->id_viaje,
        'persona_dni' => $pasajero->dni,
        'numero_asiento' => 3,
        'precio' => 1000,
        'estado' => 'reservado',
    ]);

    $this->post(route('pasajes.store'), [
        'viaje_id' => $viaje->id_viaje,
        'persona_dni' => $pasajero->dni,
        'numero_asiento' => 3,
        'precio' => 1000,
        'estado' => 'reservado',
    ])->assertSessionHasErrors('numero_asiento');

    expect(Pasaje::count())->toBe(1);
});
