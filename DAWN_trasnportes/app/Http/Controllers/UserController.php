<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $usuarios = User::query()->orderBy('name')->paginate(20);

        return view('usuario.index', compact('usuarios'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('register', ['adminUserForm' => true]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:32', 'unique:users,dni'],
            'gender' => ['required', Rule::in(['femenino', 'masculino', 'prefiero-no-decirlo'])],
            'contact' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            ...$validated,
            'name' => $validated['first_name'].' '.$validated['last_name'],
            'is_admin' => false,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $usuario): View
    {
        $this->authorizeAdmin();

        return view('usuario.perfil', [
            'user' => $usuario,
            'adminMode' => true,
            'adminEditing' => false,
        ]);
    }

    public function edit(User $usuario): View
    {
        $this->authorizeAdmin();

        return view('usuario.perfil', [
            'user' => $usuario,
            'adminMode' => true,
            'adminEditing' => true,
        ]);
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $this->authorizeAdmin();

        $persona = Persona::query()->where('dni', $usuario->dni)->first();
        $dniRules = ['required', 'string', 'max:32', Rule::unique('users', 'dni')->ignore($usuario)];
        $emailRules = ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario)];

        if ($usuario->dni !== null) {
            $dniRules[] = Rule::in([$usuario->dni]);
        }

        if ($persona !== null) {
            $emailRules[] = Rule::unique('personas', 'correo_electronico')->ignore($persona->dni, 'dni');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dni' => $dniRules,
            'gender' => ['required', Rule::in(['femenino', 'masculino', 'prefiero-no-decirlo'])],
            'contact' => ['required', 'string', 'max:32'],
            'email' => $emailRules,
        ]);

        DB::transaction(function () use ($validated, $usuario, $persona): void {
            $usuario->update([
                ...$validated,
                'name' => $validated['first_name'].' '.$validated['last_name'],
            ]);

            $personaSexo = match ($validated['gender']) {
                'femenino' => 'Femenino',
                'masculino' => 'Masculino',
                default => null,
            };

            if ($persona !== null) {
                $persona->update([
                    'nombre' => mb_substr($validated['first_name'], 0, 45),
                    'apellidos' => mb_substr($validated['last_name'], 0, 45),
                    'correo_electronico' => $validated['email'],
                    'num_telefono' => mb_substr($validated['contact'], 0, 45),
                    ...($personaSexo ? ['sexo' => $personaSexo] : []),
                ]);
            }
        });

        return redirect()->route('usuarios.show', $usuario)->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($usuario->is($request->user())) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta desde el panel.');
        }

        if ($usuario->pagos()->exists() || ($usuario->dni && DB::table('personas')->where('dni', $usuario->dni)->exists())) {
            return back()->with('error', 'No se puede eliminar este usuario porque tiene pagos o datos personales relacionados.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    private function authorizeAdmin(): void
    {
        Gate::authorize('access-development');
    }
}
