<?php

use App\Mail\PasajeCompradoMail;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function crearPagoParaCorreo(string $estado = 'aprobado', string $metodoPago = 'tarjeta_credito'): array
{
    $user = User::factory()->create([
        'name' => 'Ana Pérez',
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
    ]);

    $pago = Pago::create([
        'user_id' => $user->id,
        'referencia' => 'DAWN-01J0000000000000000000000',
        'viaje_codigo' => '1',
        'origen' => 'Leandro N. Alem',
        'destino' => 'Posadas',
        'monto' => 8500,
        'metodo_pago' => $metodoPago,
        'estado' => $estado,
        'nombre_pasajero' => 'Ana Pérez',
        'dni_pasajero' => '12345678',
    ]);

    return [$pago, $user];
}

test('el correo de un pasaje aprobado muestra los datos de la compra', function () {
    [$pago, $user] = crearPagoParaCorreo();

    (new PasajeCompradoMail($pago, $user))
        ->assertHasSubject('Tu pasaje DAWN · '.$pago->referencia)
        ->assertSeeInHtml('Tu pasaje está confirmado')
        ->assertSeeInHtml($pago->referencia)
        ->assertSeeInHtml('Ana Pérez')
        ->assertSeeInHtml('12345678')
        ->assertSeeInHtml('Leandro N. Alem')
        ->assertSeeInHtml('Posadas')
        ->assertSeeInHtml('Tarjeta de crédito')
        ->assertSeeInHtml('Aprobado')
        ->assertSeeInHtml('$ 8.500')
        ->assertSeeInText($pago->referencia);
});

test('el correo de una transferencia pendiente informa como completar el pago', function () {
    [$pago, $user] = crearPagoParaCorreo('pendiente', 'transferencia');

    (new PasajeCompradoMail($pago, $user))
        ->assertHasSubject('Tu pasaje DAWN · '.$pago->referencia)
        ->assertSeeInHtml('Recibimos tu compra')
        ->assertSeeInHtml('quedará confirmado cuando acreditemos la transferencia')
        ->assertSeeInHtml('Transferencia bancaria')
        ->assertSeeInHtml('Pendiente')
        ->assertSeeInHtml('Para completar el pago por transferencia')
        ->assertSeeInText('Recibimos tu compra');
});
