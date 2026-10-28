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
    $profileResponse->assertOk();
});

test('a visitor cannot access the profile page', function () {
    $response = $this->get(route('profile'));

    $response->assertRedirect(route('login'));
});
