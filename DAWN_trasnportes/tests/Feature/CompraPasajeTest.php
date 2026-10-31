<?php

use App\Mail\PasajeCompradoMail;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function crearUsuarioComprador(): User
{
    return User::factory()->create([
        'name' => 'Ana Pérez',
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
    ]);
}

test('un invitado vuelve al checkout despues de iniciar sesion', function () {
    $user = crearUsuarioComprador();
    $checkoutUrl = route('pagos.create', ['viaje_id' => 1]);

    $this->get($checkoutUrl)->assertRedirect(route('login'));

    $this->post(route('login.store'), [
        'username' => $user->email,
        'password' => 'password',
    ])->assertRedirect($checkoutUrl);

    $this->assertAuthenticatedAs($user);
});

test('un invitado vuelve al checkout despues de registrarse', function () {
    $checkoutUrl = route('pagos.create', ['viaje_id' => 1]);

    $this->get($checkoutUrl)->assertRedirect(route('login'));

    $this->post(route('register.store'), [
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect($checkoutUrl);

    $this->assertAuthenticated();
});

test('el checkout muestra los datos del viaje y los metodos de pago', function () {
    $user = crearUsuarioComprador();

    $this->actingAs($user)
        ->get(route('pagos.create', ['viaje_id' => 2]))
        ->assertOk()
        ->assertSee('Finalizá tu compra')
        ->assertSee('Leandro N. Alem → Córdoba')
        ->assertSee('$45.000')
        ->assertSee('value="Ana Pérez"', false)
        ->assertSee('value="12345678"', false)
        ->assertSee('Mercado Pago')
        ->assertSee('Tarjeta de crédito')
        ->assertSee('Tarjeta de débito')
        ->assertSee('Transferencia bancaria')
        ->assertSee('Confirmar pago');
});

test('una compra con tarjeta registra el pago aprobado y envia el pasaje por correo', function () {
    $user = crearUsuarioComprador();
    Mail::fake();

    $response = $this->actingAs($user)->post(route('pagos.store'), [
        'viaje_id' => '2',
        'nombre_pasajero' => 'Ana Pérez',
        'dni_pasajero' => '12345678',
        'metodo_pago' => 'tarjeta_credito',
    ]);

    $pago = Pago::firstOrFail();

    $response->assertRedirect(route('pagos.show', $pago));

    $this->assertDatabaseHas('pagos', [
        'id' => $pago->id,
        'user_id' => $user->id,
        'viaje_codigo' => '2',
        'origen' => 'Leandro N. Alem',
        'destino' => 'Córdoba',
        'monto' => 45000,
        'metodo_pago' => 'tarjeta_credito',
        'estado' => 'aprobado',
        'nombre_pasajero' => 'Ana Pérez',
        'dni_pasajero' => '12345678',
    ]);

    expect(str_starts_with($pago->referencia, 'DAWN-'))->toBeTrue();

    Mail::assertSent(PasajeCompradoMail::class, function (PasajeCompradoMail $mail) use ($pago, $user): bool {
        return $mail->hasTo($user->email)
            && $mail->pago->is($pago)
            && $mail->comprador->is($user);
    });

    $this->get(route('pagos.show', $pago))
        ->assertOk()
        ->assertSee('Pago aprobado')
        ->assertSee($pago->referencia)
        ->assertSee('Tarjeta de crédito')
        ->assertSee('$ 45.000');
});

test('una compra por transferencia queda pendiente de acreditacion y envia el pasaje por correo', function () {
    $user = crearUsuarioComprador();
    Mail::fake();

    $this->actingAs($user)->post(route('pagos.store'), [
        'viaje_id' => '4',
        'nombre_pasajero' => 'Ana Pérez',
        'dni_pasajero' => '12345678',
        'metodo_pago' => 'transferencia',
    ])->assertRedirect();

    $pago = Pago::firstOrFail();

    expect($pago->estado)->toBe('pendiente');

    Mail::assertSent(PasajeCompradoMail::class, function (PasajeCompradoMail $mail) use ($pago, $user): bool {
        return $mail->hasTo($user->email)
            && $mail->pago->is($pago)
            && $mail->pago->estado === 'pendiente'
            && $mail->comprador->is($user);
    });

    $this->get(route('pagos.show', $pago))
        ->assertOk()
        ->assertSee('Pago pendiente')
        ->assertSee('Transferencia bancaria');
});

test('un metodo de pago invalido no registra la compra ni envia correo', function () {
    $user = crearUsuarioComprador();
    $checkoutUrl = route('pagos.create', ['viaje_id' => 2]);
    Mail::fake();

    $this->actingAs($user)
        ->from($checkoutUrl)
        ->post(route('pagos.store'), [
            'viaje_id' => '2',
            'nombre_pasajero' => 'Ana Pérez',
            'dni_pasajero' => '12345678',
            'metodo_pago' => 'bitcoin',
        ])
        ->assertRedirect($checkoutUrl)
        ->assertSessionHasErrors('metodo_pago');

    $this->assertDatabaseCount('pagos', 0);

    Mail::assertNothingSent();
});

test('un viaje inexistente no abre el checkout', function () {
    $user = crearUsuarioComprador();

    $this->actingAs($user)
        ->get(route('pagos.create', ['viaje_id' => 999]))
        ->assertNotFound();
});

test('un usuario no puede ver el comprobante de otra persona', function () {
    $propietario = crearUsuarioComprador();
    $otroUsuario = User::factory()->create();

    $pago = Pago::create([
        'user_id' => $propietario->id,
        'referencia' => 'DAWN-01J0000000000000000000000',
        'viaje_codigo' => '1',
        'origen' => 'Leandro N. Alem',
        'destino' => 'Posadas',
        'monto' => 8500,
        'metodo_pago' => 'mercado_pago',
        'estado' => 'aprobado',
        'nombre_pasajero' => 'Ana Pérez',
        'dni_pasajero' => '12345678',
    ]);

    $this->actingAs($otroUsuario)
        ->get(route('pagos.show', $pago))
        ->assertForbidden();
});
