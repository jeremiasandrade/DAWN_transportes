<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use App\Services\UserAccountService;
use Closure;
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

    public function store(Request $request, UserAccountService $accountService): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:45'],
            'last_name' => ['required', 'string', 'max:45'],
            'dni' => ['required', 'string', 'max:32', 'unique:users,dni'],
            'gender' => ['required', Rule::in(['femenino', 'masculino', 'prefiero-no-decirlo'])],
            'contact' => ['required', 'string', 'max:32'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                function (string $attribute, mixed $value, Closure $fail) use ($request, $accountService): void {
                    if ($accountService->personaEmailBelongsToAnotherDni(
                        (string) $value,
                        (string) $request->input('dni'),
                    )) {
                        $fail('El correo ya está asociado a otra persona.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $accountService->createCustomer($validated);

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

    public function update(Request $request, User $usuario, UserAccountService $accountService): RedirectResponse
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
            'first_name' => ['required', 'string', 'max:45'],
            'last_name' => ['required', 'string', 'max:45'],
            'dni' => $dniRules,
            'gender' => ['required', Rule::in(['femenino', 'masculino', 'prefiero-no-decirlo'])],
            'contact' => ['required', 'string', 'max:32'],
            'email' => $emailRules,
        ]);

        $accountService->updateIdentity($usuario, $validated);

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
