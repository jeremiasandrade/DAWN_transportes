<?php

use App\Models\Ciudad;
use App\Models\Provincia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function crearCiudadParaResumen(
    string $nombre,
    string $provincia,
    float $latitud,
    float $longitud
): Ciudad {
    $provinciaModelo = Provincia::firstOrCreate(['nombre_provincia' => $provincia]);

    return Ciudad::create([
        'nombre_ciudad' => $nombre,
        'departamento' => 'Capital',
        'provincia_id' => $provinciaModelo->id_provincia,
        'latitud' => $latitud,
        'longitud' => $longitud,
    ]);
}

function simularDistanciaOsrm(float $metros): void
{
    Http::preventStrayRequests();
    Http::fake([
        'https://router.project-osrm.org/*' => Http::response([
            'code' => 'Ok',
            'routes' => [['distance' => $metros]],
        ]),
    ]);
}

test('el resumen del viaje muestra precio duracion y opciones de pago', function () {
    $origen = crearCiudadParaResumen('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadParaResumen('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);
    $fecha = now()->addDay();

    simularDistanciaOsrm(700000);

    $this->get(route('home', [
        'origin' => $origen->id_ciudad,
        'destination' => $destino->id_ciudad,
        'date' => $fecha->toDateString(),
        'vehicle' => 'doble-piso',
    ]))->assertOk()
        ->assertSee('Precio estimado')
        ->assertSee('$ 106.600')
        ->assertSee('9 h 40 min')
        ->assertSee('Ómnibus de doble piso')
        ->assertSee($fecha->format('d/m/Y'))
        ->assertSee('Opciones de pago')
        ->assertSee('Mercado Pago')
        ->assertSee('Transferencia bancaria');

    Http::assertSentCount(1);
});

test('sin fecha ni vehiculo se solicita completar los datos del resumen', function () {
    $origen = crearCiudadParaResumen('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadParaResumen('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);

    simularDistanciaOsrm(986074.8);

    $this->get(route('home', [
        'origin' => $origen->id_ciudad,
        'destination' => $destino->id_ciudad,
    ]))->assertOk()
        ->assertSee('Distancia real')
        ->assertSee('986 km')
        ->assertSee('Seleccioná la fecha y el tipo de vehículo para ver el resumen y las opciones de pago.')
        ->assertDontSee('Precio estimado')
        ->assertDontSee('Opciones de pago');
});

test('una fecha pasada no genera el resumen del viaje', function () {
    $origen = crearCiudadParaResumen('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadParaResumen('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);

    simularDistanciaOsrm(700000);

    $this->get(route('home', [
        'origin' => $origen->id_ciudad,
        'destination' => $destino->id_ciudad,
        'date' => now()->subDay()->toDateString(),
        'vehicle' => 'doble-piso',
    ]))->assertOk()
        ->assertSee('La fecha de viaje no puede ser anterior a hoy.')
        ->assertDontSee('Precio estimado');
});

test('una fecha invalida no genera el resumen del viaje', function () {
    $origen = crearCiudadParaResumen('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadParaResumen('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);

    simularDistanciaOsrm(700000);

    $this->get(route('home', [
        'origin' => $origen->id_ciudad,
        'destination' => $destino->id_ciudad,
        'date' => now()->addYear()->format('Y').'-02-30',
        'vehicle' => 'doble-piso',
    ]))->assertOk()
        ->assertSee('La fecha de viaje ingresada no es válida.')
        ->assertDontSee('Precio estimado');
});
