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
