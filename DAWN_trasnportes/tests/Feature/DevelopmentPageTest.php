<?php

use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('a guest is redirected to login from the development page', function () {
    $this->get('/desarrollo')->assertRedirect(route('login'));
});

test('an authenticated non-admin is forbidden from the development page', function () {
    $this->actingAs(User::factory()->create())
        ->get('/desarrollo')
        ->assertForbidden();
});

test('an administrator can see the development catalog', function () {
    $user = User::factory()->create(['dni' => 'admin-123']);

    Persona::create([
        'dni' => 'admin-123',
        'nombre' => 'Admin',
        'apellidos' => 'Proyecto',
        'correo_electronico' => 'admin@example.com',
        'contrasenia' => 'hashed-password',
        'num_telefono' => '555123456',
        'sexo' => 'Masculino',
    ]);

    $roleId = DB::table('roles')->insertGetId(['nombre_rol' => 'Administrador'], 'id_rol');

    DB::table('administradores')->insert([
        'persona_dni' => 'admin-123',
        'rol_id' => $roleId,
    ]);

    $this->actingAs($user)
        ->get('/desarrollo')
        ->assertOk()
        ->assertSee('Catálogo de rutas')
        ->assertSee('Rutas registradas')
        ->assertSee('profile.password.update')
        ->assertSee('Destinos')
        ->assertSee('Clientes');
});
