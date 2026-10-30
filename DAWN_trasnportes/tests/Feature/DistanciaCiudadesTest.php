<?php

use App\Models\Ciudad;
use App\Models\Provincia;
use App\Models\Ruta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function crearCiudadConCoordenadas(
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

test('la creacion de una ruta calcula la distancia real con OSRM', function () {
    $origen = crearCiudadConCoordenadas('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadConCoordenadas('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);

    Http::preventStrayRequests();
    Http::fake([
        'https://router.project-osrm.org/*' => Http::response([
            'code' => 'Ok',
            'routes' => [['distance' => 986074.8]],
        ]),
    ]);

    $this->post(route('rutas.store'), [
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $destino->id_ciudad,
        'distancia' => 1,
    ])->assertRedirect(route('rutas.index'));

    $ruta = Ruta::first();

    expect($ruta)->not->toBeNull()
        ->and($ruta->distancia)->toBe(986);
});

test('la consulta de distancia devuelve el valor calculado por OSRM', function () {
    $origen = crearCiudadConCoordenadas('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadConCoordenadas('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);

    Http::preventStrayRequests();
    Http::fake([
        'https://router.project-osrm.org/*' => Http::response([
            'code' => 'Ok',
            'routes' => [['distance' => 986074.8]],
        ]),
    ]);

    $this->getJson(route('rutas.distancia', [
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $destino->id_ciudad,
    ]))->assertOk()
        ->assertJson([
            'distancia' => 986,
            'calculada' => true,
        ]);
});

test('la busqueda del inicio muestra la distancia real entre origen y destino', function () {
    $origen = crearCiudadConCoordenadas('Posadas', 'Misiones', -27.482, -55.896);
    $destino = crearCiudadConCoordenadas('Buenos Aires', 'Ciudad Autónoma de Buenos Aires', -34.603, -58.381);

    Http::preventStrayRequests();
    Http::fake([
        'https://router.project-osrm.org/*' => Http::response([
            'code' => 'Ok',
            'routes' => [['distance' => 986074.8]],
        ]),
    ]);

    $this->get(route('home', [
        'origin' => $origen->id_ciudad,
        'destination' => $destino->id_ciudad,
    ]))->assertOk()
        ->assertSee('Distancia real')
        ->assertSee('986 km');

    Http::assertSentCount(1);
});

test('el comando de importacion guarda las localidades con sus coordenadas', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://apis.datos.gob.ar/georef/api/localidades*' => Http::response([
            'localidades' => [
                [
                    'nombre' => 'Posadas',
                    'provincia' => ['nombre' => 'Misiones'],
                    'departamento' => ['nombre' => 'Capital'],
                    'centroide' => ['lat' => -27.482, 'lon' => -55.896],
                ],
                [
                    'nombre' => 'Buenos Aires',
                    'provincia' => ['nombre' => 'Ciudad Autónoma de Buenos Aires'],
                    'departamento' => ['nombre' => 'Comuna 1'],
                    'centroide' => ['lat' => -34.603, 'lon' => -58.381],
                ],
            ],
        ]),
    ]);

    $this->artisan('ciudades:importar')
        ->assertSuccessful();

    expect(Ciudad::count())->toBe(2)
        ->and(Provincia::count())->toBe(2);

    $posadas = Ciudad::where('nombre_ciudad', 'Posadas')->first();

    expect($posadas)->not->toBeNull()
        ->and($posadas->latitud)->toBe(-27.482)
        ->and($posadas->longitud)->toBe(-55.896)
        ->and($posadas->departamento)->toBe('Capital');
});
