<?php

use App\Models\User;
use App\Services\UserAccountService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('creating a customer account persists its user persona and client records atomically', function () {
    $user = app(UserAccountService::class)->createCustomer([
        'first_name' => 'Lucía',
        'last_name' => 'Gómez',
        'dni' => '44332211',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'lucia@example.com',
        'password' => 'password123',
    ]);

    expect($user->is_admin)->toBeFalse();
    $this->assertDatabaseHas('personas', [
        'dni' => '44332211',
        'sexo' => 'Femenino',
        'correo_electronico' => 'lucia@example.com',
    ]);
    $this->assertDatabaseHas('clientes', ['persona_dni' => '44332211']);
});

test('creating an administrator account persists its admin role without making it a client', function () {
    $user = app(UserAccountService::class)->createAdministrator([
        'first_name' => 'Admin',
        'last_name' => 'Proyecto',
        'dni' => '99887766',
        'gender' => 'prefiero-no-decirlo',
        'contact' => '555987654',
        'email' => 'admin@example.com',
        'password' => 'password123',
    ]);

    expect($user->is_admin)->toBeTrue();
    $this->assertDatabaseHas('personas', [
        'dni' => '99887766',
        'sexo' => 'No especifica',
    ]);
    $this->assertDatabaseHas('administradores', ['persona_dni' => '99887766']);
    $this->assertDatabaseMissing('clientes', ['persona_dni' => '99887766']);
});

test('updating account identity and password keeps the linked persona synchronized', function () {
    $accountService = app(UserAccountService::class);
    $user = $accountService->createCustomer([
        'first_name' => 'Lucía',
        'last_name' => 'Gómez',
        'dni' => '44332211',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'lucia@example.com',
        'password' => 'password123',
    ]);

    $accountService->updateIdentity($user, [
        'first_name' => 'Lucía María',
        'last_name' => 'Gómez',
        'dni' => '44332211',
        'gender' => 'prefiero-no-decirlo',
        'contact' => '555765432',
        'email' => 'lucia.maria@example.com',
    ]);
    $accountService->updatePassword($user, 'new-password123');

    $user->refresh();
    $this->assertDatabaseHas('personas', [
        'dni' => '44332211',
        'nombre' => 'Lucía María',
        'correo_electronico' => 'lucia.maria@example.com',
        'num_telefono' => '555765432',
        'sexo' => 'No especifica',
    ]);
    expect(Hash::check('new-password123', $user->password))->toBeTrue();
    expect(Hash::check('new-password123', $user->persona->contrasenia))->toBeTrue();
});

test('the user factory creates the linked persona before persisting a user', function () {
    $user = User::factory()->create();

    $this->assertDatabaseHas('personas', ['dni' => $user->dni]);
    $this->assertDatabaseHas('clientes', ['persona_dni' => $user->dni]);
});

test('the database rejects a user with a DNI that has no persona', function () {
    expect(fn () => User::query()->create([
        'name' => 'Cuenta huérfana',
        'first_name' => 'Cuenta',
        'last_name' => 'Huérfana',
        'dni' => '88776655',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'orphan@example.com',
        'password' => 'password123',
    ]))->toThrow(QueryException::class);
});
