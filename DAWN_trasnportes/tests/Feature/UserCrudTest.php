<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('only an administrator can access the user listing', function () {
    $this->get(route('usuarios.index'))->assertRedirect(route('login'));

    $this->actingAs(User::factory()->create())
        ->get(route('usuarios.index'))
        ->assertForbidden();
});

test('an administrator can create a user without signing in as that user', function () {
    $administrator = User::factory()->administrator()->create();

    $response = $this->actingAs($administrator)->post(route('usuarios.store'), [
        'first_name' => 'Lucía',
        'last_name' => 'Gómez',
        'dni' => '44332211',
        'gender' => 'femenino',
        'contact' => '555123456',
        'email' => 'lucia@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'is_admin' => true,
    ]);

    $response->assertRedirect(route('usuarios.index'));
    $this->assertAuthenticatedAs($administrator);
    $this->assertDatabaseHas('users', [
        'name' => 'Lucía Gómez',
        'email' => 'lucia@example.com',
        'is_admin' => false,
    ]);
});

test('an administrator can view and update another user through the profile view', function () {
    $administrator = User::factory()->administrator()->create();
    $user = User::factory()->create([
        'name' => 'Lucía Gómez',
        'first_name' => 'Lucía',
        'last_name' => 'Gómez',
        'dni' => '44332211',
        'gender' => 'masculino',
    ]);
    $this->actingAs($administrator)
        ->get(route('usuarios.show', $user))
        ->assertOk()
        ->assertSee('Lucía Gómez')
        ->assertSee('Tipo de cuenta');

    $this->get(route('usuarios.edit', $user))
        ->assertOk()
        ->assertSee('data-invalid-dialog="edit"', false)
        ->assertSee('action="'.route('usuarios.update', $user).'"', false);

    $response = $this->put(route('usuarios.update', $user), [
        'first_name' => 'Lucía María',
        'last_name' => 'Gómez',
        'dni' => '44332211',
        'gender' => 'femenino',
        'contact' => '555765432',
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('usuarios.show', $user));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Lucía María Gómez',
        'contact' => '555765432',
    ]);
    $this->assertDatabaseHas('personas', [
        'dni' => '44332211',
        'nombre' => 'Lucía María',
        'correo_electronico' => $user->email,
        'num_telefono' => '555765432',
        'sexo' => 'Femenino',
    ]);
});

test('an administrator cannot change a user document number from the CRUD', function () {
    $administrator = User::factory()->administrator()->create();
    $user = User::factory()->create(['dni' => '44332211']);

    $response = $this->actingAs($administrator)->put(route('usuarios.update', $user), [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'dni' => '11223344',
        'gender' => 'femenino',
        'contact' => $user->contact,
        'email' => $user->email,
    ]);

    $response->assertSessionHasErrors('dni');
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'dni' => '44332211',
    ]);
});

test('an administrator can delete an unlinked user but cannot delete a user with legacy identity data', function () {
    $administrator = User::factory()->administrator()->create();
    $unlinkedUser = User::factory()->create(['dni' => null]);
    $linkedUser = User::factory()->client()->create();

    $this->actingAs($administrator)
        ->delete(route('usuarios.destroy', $unlinkedUser))
        ->assertRedirect(route('usuarios.index'));

    $this->assertDatabaseMissing('users', ['id' => $unlinkedUser->id]);

    $this->delete(route('usuarios.destroy', $linkedUser))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $linkedUser->id]);
});

test('an administrator cannot delete their own account', function () {
    $administrator = User::factory()->administrator()->create();

    $this->actingAs($administrator)
        ->delete(route('usuarios.destroy', $administrator))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $administrator->id]);
});
