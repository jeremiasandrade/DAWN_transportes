<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a guest is redirected to login from the development page', function () {
    $this->get('/desarrollo')->assertRedirect(route('login'));
});

test('an authenticated non-admin is forbidden from the development page', function () {
    $this->actingAs(User::factory()->create())
        ->get('/desarrollo')
        ->assertForbidden();
});

test('an administrator can see the crud management dashboard', function () {
    $user = User::factory()->administrator()->create(['dni' => 'admin-123']);

    $this->actingAs($user)
        ->get('/desarrollo')
        ->assertOk()
        ->assertSee('Panel de administración')
        ->assertSee('href="'.route('home.public').'"', false)
        ->assertSee('aria-label="Crear en Destinos"', false)
        ->assertSee('aria-label="Listar Destinos"', false)
        ->assertSee('aria-label="Eliminar registros de Destinos desde el listado"', false)
        ->assertSee('href="'.route('destinos.create').'"', false)
        ->assertSee('href="'.route('destinos.index').'"', false)
        ->assertSee('aria-label="Crear en Usuarios"', false)
        ->assertSee('href="'.route('usuarios.create').'"', false)
        ->assertSee('aria-label="Crear en Viajes"', false)
        ->assertSee('href="'.route('admin.viajes.create').'"', false)
        ->assertSee('href="'.route('admin.viajes.index').'"', false)
        ->assertSee('Destinos')
        ->assertSee('Viajes recomendados')
        ->assertSee('Panel Admin')
        ->assertSee('En preparación');
});

test('an administrator is redirected to the management dashboard from home', function () {
    $administrator = User::factory()->administrator()->create();

    $this->actingAs($administrator)
        ->get(route('home'))
        ->assertRedirect(route('desarrollo'));
});

test('an administrator can open the public home from its dedicated route', function () {
    $administrator = User::factory()->administrator()->create();

    $this->actingAs($administrator)
        ->get(route('home.public'))
        ->assertOk()
        ->assertViewIs('home');
});
