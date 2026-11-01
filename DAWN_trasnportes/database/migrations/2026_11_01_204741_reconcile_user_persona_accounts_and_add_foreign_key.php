<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->enum('sexo', ['Masculino', 'Femenino', 'No especifica'])->change();
        });

        $roleId = DB::table('roles')->where('nombre_rol', 'Administrador')->value('id_rol');

        DB::table('users')
            ->whereNotNull('dni')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('personas')
                    ->whereColumn('personas.dni', 'users.dni');
            })
            ->orderBy('id')
            ->get()
            ->each(function (object $user) use (&$roleId): void {
                $nameParts = preg_split('/\s+/', trim((string) $user->name), 2) ?: [];
                $firstName = trim((string) ($user->first_name ?: ($nameParts[0] ?? 'Usuario')));
                $lastName = trim((string) ($user->last_name ?: ($nameParts[1] ?? 'Sin apellido')));
                $gender = mb_strtolower((string) $user->gender);
                $sexo = match ($gender) {
                    'femenino' => 'Femenino',
                    'masculino' => 'Masculino',
                    default => 'No especifica',
                };

                DB::table('personas')->insert([
                    'dni' => $user->dni,
                    'nombre' => mb_substr($firstName, 0, 45),
                    'apellidos' => mb_substr($lastName, 0, 45),
                    'correo_electronico' => $user->email,
                    'contrasenia' => $user->password,
                    'num_telefono' => mb_substr((string) $user->contact, 0, 45),
                    'sexo' => $sexo,
                ]);

                if ($user->is_admin) {
                    if ($roleId === null) {
                        $roleId = DB::table('roles')->insertGetId([
                            'nombre_rol' => 'Administrador',
                        ], 'id_rol');
                    }

                    DB::table('administradores')->insertOrIgnore([
                        'persona_dni' => $user->dni,
                        'rol_id' => $roleId,
                    ]);

                    return;
                }

                DB::table('clientes')->insertOrIgnore(['persona_dni' => $user->dni]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('dni', 'users_dni_personas_foreign')
                ->references('dni')
                ->on('personas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('personas')->where('sexo', 'No especifica')->exists()) {
            throw new RuntimeException('No se puede revertir mientras existan personas con sexo No especifica.');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_dni_personas_foreign');
        });

        Schema::table('personas', function (Blueprint $table) {
            $table->enum('sexo', ['Masculino', 'Femenino'])->change();
        });
    }
};
