<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a user can sign in with their email and is redirected to home', function () {
    $user = User::create([
        'name' => 'Ana Pérez',
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post(route('login.store'), [
        'username' => 'ana@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('the home page renders the trip search and offer cards', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('href="'.route('home').'"', false)
        ->assertSee('data-theme-toggle', false)
        ->assertSee('Cambiar a tema oscuro')
        ->assertSee('Buscar Viajes disponibles')
        ->assertSee('Ofertas y Destinos Destacados')
        ->assertSee('Leandro N. Alem - Posadas')
        ->assertSee('Leandro N. Alem - Córdoba')
        ->assertSee('Leandro N. Alem - Buenos Aires');
});

test('the home page shows nine featured trips across three carousel pages', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('data-carousel-page="0"', false)
        ->assertSee('data-carousel-page="1"', false)
        ->assertSee('data-carousel-page="2"', false)
        ->assertSee('data-carousel-indicator="0"', false)
        ->assertSee('data-carousel-indicator="1"', false)
        ->assertSee('data-carousel-indicator="2"', false)
        ->assertSee('6000', false)
        ->assertSee('Leandro N. Alem - Mendoza');
});

test('a visitor is sent to registration from the profile button', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('href="'.route('register').'"', false);
});

test('an authenticated user is sent to the empty profile page', function () {
    $this->actingAs(User::factory()->create());

    $homeResponse = $this->get(route('home'));
    $profileResponse = $this->get(route('profile'));

    $homeResponse->assertOk()
        ->assertSee('href="'.route('profile').'"', false);
    $profileResponse->assertOk()
        ->assertSee('Mi cuenta')
        ->assertSee('Mis datos')
        ->assertSee('Cerrar sesión');
});

test('a visitor cannot access the profile page', function () {
    $response = $this->get(route('profile'));

    $response->assertRedirect(route('login'));
});

test('an authenticated user can update their profile information', function () {
    $user = User::factory()->create([
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
    ]);

    $response = $this->actingAs($user)->put(route('profile.update'), [
        'first_name' => 'Ana María',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555987654',
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('profile'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Ana María Pérez',
        'first_name' => 'Ana María',
        'contact' => '555987654',
    ]);
});

test('invalid profile updates keep the editor open for correction', function () {
    $user = User::factory()->create([
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
    ]);

    $response = $this->actingAs($user)->followingRedirects()->from(route('profile'))->put(route('profile.update'), [
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'not-an-email',
    ]);

    $response->assertOk()
        ->assertSee('data-invalid-dialog="edit"', false)
        ->assertSee('The email field must be a valid email address.');
});

test('an authenticated user can change their password with the current password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $response = $this->actingAs($user)->put(route('profile.password.update'), [
        'current_password' => 'old-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('profile'));
    $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
});

test('a user can end their session from the profile page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});
