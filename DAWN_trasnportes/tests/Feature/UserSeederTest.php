<?php

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user seeder creates five clients and one working administrator', function () {
    $this->seed(UserSeeder::class);
    $this->seed(UserSeeder::class);

    $administrator = User::query()->where('email', 'admin@dawn.test')->firstOrFail();
    $clients = User::query()->where('is_admin', false)->get();

    expect(User::query()->count())->toBe(6);
    expect($administrator->is_admin)->toBeTrue();
    expect($clients)->toHaveCount(5);

    $this->assertDatabaseCount('clientes', 5);
    $this->assertDatabaseHas('administradores', [
        'persona_dni' => $administrator->dni,
    ]);

    $this->actingAs($administrator)
        ->get('/desarrollo')
        ->assertOk();
});
