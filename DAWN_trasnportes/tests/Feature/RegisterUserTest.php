<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a visitor can register and is authenticated', function () {
    $response = $this->post(route('register.store'), [
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'ana@example.com')->firstOrFail();

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('users', [
        'name' => 'Ana Pérez',
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
    ]);
    expect($user->password)->not->toBe('password123');
});

test('registration rejects mismatched password confirmation', function () {
    $response = $this->from(route('register'))->post(route('register.store'), [
        'first_name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '12345678',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'ana@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different123',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});
