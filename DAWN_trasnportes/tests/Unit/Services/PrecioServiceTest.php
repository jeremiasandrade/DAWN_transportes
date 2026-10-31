<?php

use App\Services\PrecioService;

test('estima el precio segun la tarifa oficial y el tipo de vehiculo', function (string $tipoVehiculo, float $precioEsperado) {
    $service = new PrecioService;

    expect($service->estimar(700, $tipoVehiculo))->toBe($precioEsperado);
})->with([
    'minibus' => ['minibus', 78800.0],
    'piso elevado' => ['piso-elevado', 92700.0],
    'doble piso' => ['doble-piso', 106600.0],
]);

test('aplica la tarifa minima para viajes cortos', function () {
    $service = new PrecioService;

    expect($service->estimar(1, 'minibus'))->toBe(2500.0);
});

test('no estima el precio de un tipo de vehiculo desconocido', function () {
    $service = new PrecioService;

    expect($service->estimar(700, 'desconocido'))->toBeNull();
});

test('calcula y formatea la duracion estimada del viaje', function () {
    $service = new PrecioService;

    expect($service->duracionMinutos(700))->toBe(580)
        ->and($service->formatearDuracion(580))->toBe('9 h 40 min')
        ->and($service->formatearDuracion(120))->toBe('2 h')
        ->and($service->formatearDuracion(45))->toBe('45 min');
});
