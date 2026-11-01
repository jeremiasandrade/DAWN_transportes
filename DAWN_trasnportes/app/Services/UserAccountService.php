<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAccountService
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createCustomer(array $attributes): User
    {
        return $this->createAccount($attributes, false);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createAdministrator(array $attributes): User
    {
        return $this->createAccount($attributes, true);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateIdentity(User $user, array $attributes): void
    {
        DB::transaction(function () use ($user, $attributes): void {
            $personaData = $this->personaAttributes($attributes, $user->password);

            Persona::query()->updateOrCreate(
                ['dni' => $attributes['dni']],
                $personaData,
            );

            $user->update([
                ...$attributes,
                'name' => $attributes['first_name'].' '.$attributes['last_name'],
            ]);

            $this->ensureDomainRole($user->dni, $user->is_admin);
        });
    }

    public function updatePassword(User $user, string $password): void
    {
        DB::transaction(function () use ($user, $password): void {
            $user->update(['password' => $password]);

            if ($user->dni !== null) {
                Persona::query()->where('dni', $user->dni)->update([
                    'contrasenia' => $user->password,
                ]);
            }
        });
    }

    public function personaEmailBelongsToAnotherDni(string $email, string $dni): bool
    {
        return DB::table('personas')
            ->where('correo_electronico', $email)
            ->where('dni', '<>', $dni)
            ->exists();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createAccount(array $attributes, bool $isAdmin): User
    {
        return DB::transaction(function () use ($attributes, $isAdmin): User {
            $dni = (string) $attributes['dni'];

            if (! $isAdmin && DB::table('administradores')->where('persona_dni', $dni)->exists()) {
                throw ValidationException::withMessages([
                    'dni' => 'Este documento pertenece a una cuenta administrativa.',
                ]);
            }

            $password = (string) $attributes['password'];
            $passwordHash = Hash::needsRehash($password) ? Hash::make($password) : $password;
            $personaData = $this->personaAttributes($attributes, $passwordHash);

            Persona::query()->updateOrCreate(['dni' => $dni], $personaData);

            $user = User::query()->create([
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'name' => $attributes['first_name'].' '.$attributes['last_name'],
                'dni' => $dni,
                'gender' => mb_strtolower((string) $attributes['gender']),
                'contact' => $attributes['contact'],
                'email' => $attributes['email'],
                'password' => $passwordHash,
                'is_admin' => $isAdmin,
            ]);

            $this->ensureDomainRole($dni, $isAdmin);

            return $user;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, string>
     */
    private function personaAttributes(array $attributes, string $passwordHash): array
    {
        return [
            'nombre' => mb_substr((string) $attributes['first_name'], 0, 45),
            'apellidos' => mb_substr((string) $attributes['last_name'], 0, 45),
            'correo_electronico' => (string) $attributes['email'],
            'contrasenia' => $passwordHash,
            'num_telefono' => mb_substr((string) $attributes['contact'], 0, 45),
            'sexo' => Persona::sexoDesdeGenero((string) $attributes['gender']),
        ];
    }

    private function ensureDomainRole(string $dni, bool $isAdmin): void
    {
        if (! $isAdmin) {
            Cliente::query()->firstOrCreate(['persona_dni' => $dni]);

            return;
        }

        $roleId = DB::table('roles')->where('nombre_rol', 'Administrador')->value('id_rol');

        if ($roleId === null) {
            $roleId = DB::table('roles')->insertGetId(['nombre_rol' => 'Administrador'], 'id_rol');
        }

        DB::table('administradores')->updateOrInsert(
            ['persona_dni' => $dni],
            ['rol_id' => $roleId],
        );
    }
}
