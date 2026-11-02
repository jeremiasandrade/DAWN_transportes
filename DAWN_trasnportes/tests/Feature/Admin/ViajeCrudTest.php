<?php

use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\Pasaje;
use App\Models\Persona;
use App\Models\Provincia;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Viaje;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/** @return array{admin: User, origen: Ciudad, destino: Ciudad, cliente: Cliente, vehiculo: Vehiculo} */
function prepararViajeCrud(): array
{
    $admin = User::factory()->administrator()->create();
    $origen = crearCiudadViajeCrud('Posadas', 'Misiones', 1);
    $destino = crearCiudadViajeCrud('Corrientes', 'Corrientes', 2);
    $clientePersona = crearPersonaViajeCrud('11223344', 'Cliente', 'Prueba', 'cliente@example.com');
    $choferPersona = crearPersonaViajeCrud('44332211', 'Chofer', 'Prueba', 'chofer@example.com');
    $cliente = Cliente::query()->create(['persona_dni' => $clientePersona->dni]);
    DB::table('choferes')->insert(['persona_dni' => $choferPersona->dni]);
    Ruta::query()->create([
        'id_ciudad_partida' => $origen->id_ciudad,
        'id_ciudad_llegada' => $destino->id_ciudad,
        'distancia' => 300,
    ]);
    $vehiculo = Vehiculo::query()->create([
        'modelo' => 'Sprinter',
        'marca' => 'Mercedes',
        'patente' => 'AB123CD',
        'chofer_dni' => $choferPersona->dni,
    ]);

    return compact('admin', 'origen', 'destino', 'cliente', 'vehiculo');
}

function crearCiudadViajeCrud(string $nombre, string $provinciaNombre, int $provinciaId): Ciudad
{
    $provincia = Provincia::query()->create([
        'id_provincia' => $provinciaId,
        'nombre_provincia' => $provinciaNombre,
    ]);

    return Ciudad::query()->create([
        'nombre_ciudad' => $nombre,
        'provincia_id' => $provincia->id_provincia,
        'latitud' => 0,
        'longitud' => 0,
    ]);
}

function crearPersonaViajeCrud(string $dni, string $nombre, string $apellido, string $correo): Persona
{
    return Persona::query()->create([
        'dni' => $dni,
        'nombre' => $nombre,
        'apellidos' => $apellido,
        'correo_electronico' => $correo,
        'contrasenia' => 'hashed-password',
        'num_telefono' => '555123456',
        'sexo' => 'No especifica',
    ]);
}

test('only an administrator can access the travel management CRUD', function () {
    $this->get(route('admin.viajes.index'))->assertRedirect(route('login'));

    $this->actingAs(User::factory()->client()->create())
        ->get(route('admin.viajes.index'))
        ->assertForbidden();
});

test('an administrator can create a travel using an existing route, client, and vehicle', function () {
    $data = prepararViajeCrud();
    $payload = [
        'id_ciudad_partida' => $data['origen']->id_ciudad,
        'id_ciudad_llegada' => $data['destino']->id_ciudad,
        'cliente_dni' => $data['cliente']->persona_dni,
        'vehiculo_id' => $data['vehiculo']->id_vehiculo,
        'fecha_partida' => '2026-11-15T08:00',
        'fecha_llegada' => '2026-11-15T14:00',
    ];

    $response = $this->actingAs($data['admin'])->post(route('admin.viajes.store'), $payload);

    $viaje = Viaje::query()->firstOrFail();
    $response->assertRedirect(route('admin.viajes.index'));
    $this->assertDatabaseHas('viajes', [
        'id_viaje' => $viaje->id_viaje,
        'id_ciudad_partida' => $data['origen']->id_ciudad,
        'id_ciudad_llegada' => $data['destino']->id_ciudad,
        'cliente_dni' => $data['cliente']->persona_dni,
        'vehiculo_id' => $data['vehiculo']->id_vehiculo,
    ]);

    $this->get(route('admin.viajes.index'))
        ->assertOk()
        ->assertSee('Posadas')
        ->assertSee('Corrientes');
});

test('an administrator can search trips by city or province and choose the sorting order', function () {
    $data = prepararViajeCrud();
    $resistencia = crearCiudadViajeCrud('Resistencia', 'Chaco', 3);
    $trips = [
        [$data['origen'], $data['destino'], '2026-11-15 08:00:00'],
        [$data['destino'], $resistencia, '2026-11-16 08:00:00'],
        [$resistencia, $data['origen'], '2026-11-17 08:00:00'],
    ];

    foreach ($trips as [$origin, $destination, $departure]) {
        Ruta::query()->firstOrCreate([
            'id_ciudad_partida' => $origin->id_ciudad,
            'id_ciudad_llegada' => $destination->id_ciudad,
        ], ['distancia' => 300]);

        Viaje::query()->create([
            'id_ciudad_partida' => $origin->id_ciudad,
            'id_ciudad_llegada' => $destination->id_ciudad,
            'cliente_dni' => $data['cliente']->persona_dni,
            'vehiculo_id' => $data['vehiculo']->id_vehiculo,
            'fecha_partida' => $departure,
            'fecha_llegada' => date('Y-m-d H:i:s', strtotime($departure.' +6 hours')),
        ]);
    }

    $this->actingAs($data['admin'])
        ->get(route('admin.viajes.index', ['q' => 'Resistencia', 'orden' => 'nombre_desc']))
        ->assertOk()
        ->assertSeeInOrder(['Resistencia → Posadas', 'Corrientes → Resistencia'])
        ->assertDontSee('Posadas → Corrientes')
        ->assertSee('name="q" type="search" value="Resistencia"', false)
        ->assertSee('value="nombre_desc" selected', false);

    $this->get(route('admin.viajes.index'))
        ->assertOk()
        ->assertSeeInOrder(['Resistencia → Posadas', 'Corrientes → Resistencia', 'Posadas → Corrientes'])
        ->assertSee('value="provincia" selected', false);
});

test('a travel cannot be created for a city pair without a registered route', function () {
    $data = prepararViajeCrud();
    $otroDestino = crearCiudadViajeCrud('Resistencia', 'Chaco', 3);

    $this->actingAs($data['admin'])
        ->from(route('admin.viajes.create'))
        ->post(route('admin.viajes.store'), [
            'id_ciudad_partida' => $data['origen']->id_ciudad,
            'id_ciudad_llegada' => $otroDestino->id_ciudad,
            'cliente_dni' => $data['cliente']->persona_dni,
            'vehiculo_id' => $data['vehiculo']->id_vehiculo,
            'fecha_partida' => '2026-11-15T08:00',
            'fecha_llegada' => '2026-11-15T14:00',
        ])
        ->assertRedirect(route('admin.viajes.create'))
        ->assertSessionHasErrors('id_ciudad_llegada');

    $this->assertDatabaseCount('viajes', 0);
});

test('a travel arrival must be later than its departure', function () {
    $data = prepararViajeCrud();

    $this->actingAs($data['admin'])
        ->from(route('admin.viajes.create'))
        ->post(route('admin.viajes.store'), [
            'id_ciudad_partida' => $data['origen']->id_ciudad,
            'id_ciudad_llegada' => $data['destino']->id_ciudad,
            'cliente_dni' => $data['cliente']->persona_dni,
            'vehiculo_id' => $data['vehiculo']->id_vehiculo,
            'fecha_partida' => '2026-11-15T14:00',
            'fecha_llegada' => '2026-11-15T08:00',
        ])
        ->assertRedirect(route('admin.viajes.create'))
        ->assertSessionHasErrors('fecha_llegada');

    $this->assertDatabaseCount('viajes', 0);
});

test('an administrator can open the travel forms and update a travel', function () {
    $data = prepararViajeCrud();
    $viaje = Viaje::query()->create([
        'id_ciudad_partida' => $data['origen']->id_ciudad,
        'id_ciudad_llegada' => $data['destino']->id_ciudad,
        'cliente_dni' => $data['cliente']->persona_dni,
        'vehiculo_id' => $data['vehiculo']->id_vehiculo,
        'fecha_partida' => '2026-11-15 08:00:00',
        'fecha_llegada' => '2026-11-15 14:00:00',
    ]);

    $this->actingAs($data['admin'])
        ->get(route('admin.viajes.create'))
        ->assertOk()
        ->assertSee('Crear viaje')
        ->assertSee('Posadas')
        ->assertSee('Sprinter');

    $this->get(route('admin.viajes.show', $viaje))
        ->assertOk()
        ->assertSee('Posadas')
        ->assertSee('Corrientes');

    $this->get(route('admin.viajes.edit', $viaje))
        ->assertOk()
        ->assertSee('Editar viaje');

    $this->put(route('admin.viajes.update', $viaje), [
        'id_ciudad_partida' => $data['origen']->id_ciudad,
        'id_ciudad_llegada' => $data['destino']->id_ciudad,
        'cliente_dni' => $data['cliente']->persona_dni,
        'vehiculo_id' => $data['vehiculo']->id_vehiculo,
        'fecha_partida' => '2026-11-16T09:00',
        'fecha_llegada' => '2026-11-16T15:00',
    ])->assertRedirect(route('admin.viajes.show', $viaje));

    $this->assertDatabaseHas('viajes', [
        'id_viaje' => $viaje->id_viaje,
        'fecha_partida' => '2026-11-16 09:00:00',
    ]);
});

test('an administrator can delete a travel without tickets but cannot delete one with tickets', function () {
    $data = prepararViajeCrud();
    $viajeSinPasajes = crearViajeCrudPara($data, '2026-11-15 08:00:00');
    $viajeConPasajes = crearViajeCrudPara($data, '2026-11-16 08:00:00');
    $pasajero = crearPersonaViajeCrud('55667788', 'Pasajero', 'Prueba', 'pasajero@example.com');
    Pasaje::query()->create([
        'viaje_id' => $viajeConPasajes->id_viaje,
        'persona_dni' => $pasajero->dni,
        'numero_asiento' => 1,
        'precio' => 1000,
        'estado' => 'reservado',
    ]);

    $this->actingAs($data['admin'])
        ->delete(route('admin.viajes.destroy', $viajeSinPasajes))
        ->assertRedirect(route('admin.viajes.index'));
    $this->assertDatabaseMissing('viajes', ['id_viaje' => $viajeSinPasajes->id_viaje]);

    $this->delete(route('admin.viajes.destroy', $viajeConPasajes))
        ->assertRedirect()
        ->assertSessionHas('error');
    $this->assertDatabaseHas('viajes', ['id_viaje' => $viajeConPasajes->id_viaje]);
});

function crearViajeCrudPara(array $data, string $fechaPartida): Viaje
{
    return Viaje::query()->create([
        'id_ciudad_partida' => $data['origen']->id_ciudad,
        'id_ciudad_llegada' => $data['destino']->id_ciudad,
        'cliente_dni' => $data['cliente']->persona_dni,
        'vehiculo_id' => $data['vehiculo']->id_vehiculo,
        'fecha_partida' => $fechaPartida,
        'fecha_llegada' => date('Y-m-d H:i:s', strtotime($fechaPartida.' +6 hours')),
    ]);
}
