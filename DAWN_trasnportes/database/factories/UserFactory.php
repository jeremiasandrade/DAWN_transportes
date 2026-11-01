<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'name' => $firstName.' '.$lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'dni' => fake()->unique()->numerify('########'),
            'gender' => fake()->randomElement(['Masculino', 'Femenino']),
            'contact' => fake()->numerify('##########'),
            'is_admin' => false,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => false,
        ])->afterCreating(function (User $user): void {
            $this->createPersona($user);

            Cliente::query()->firstOrCreate([
                'persona_dni' => $user->dni,
            ]);
        });
    }

    public function administrator(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ])->afterCreating(function (User $user): void {
            $this->createPersona($user);

            $roleId = DB::table('roles')
                ->where('nombre_rol', 'Administrador')
                ->value('id_rol');

            if ($roleId === null) {
                $roleId = DB::table('roles')->insertGetId([
                    'nombre_rol' => 'Administrador',
                ], 'id_rol');
            }

            DB::table('administradores')->updateOrInsert(
                ['persona_dni' => $user->dni],
                ['rol_id' => $roleId],
            );
        });
    }

    private function createPersona(User $user): void
    {
        Persona::query()->firstOrCreate(
            ['dni' => $user->dni],
            [
                'nombre' => $user->first_name,
                'apellidos' => $user->last_name,
                'correo_electronico' => $user->email,
                'contrasenia' => $user->password,
                'num_telefono' => $user->contact,
                'sexo' => $user->gender,
            ],
        );
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
