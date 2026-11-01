<?php

namespace App\Http\Controllers;

use App\Services\UserAccountService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('usuario.perfil', ['user' => $request->user()]);
    }

    public function update(Request $request, UserAccountService $accountService): RedirectResponse
    {
        $user = $request->user();
        $dniRules = ['required', 'string', 'max:32', Rule::unique('users', 'dni')->ignore($user)];
        $emailRules = [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($user),
            function (string $attribute, mixed $value, Closure $fail) use ($request, $accountService): void {
                if ($accountService->personaEmailBelongsToAnotherDni(
                    (string) $value,
                    (string) $request->input('dni'),
                )) {
                    $fail('El correo ya está asociado a otra persona.');
                }
            },
        ];

        if ($user->dni !== null) {
            $dniRules[] = Rule::in([$user->dni]);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:45'],
            'last_name' => ['required', 'string', 'max:45'],
            'dni' => $dniRules,
            'gender' => ['required', Rule::in(['femenino', 'masculino', 'prefiero-no-decirlo'])],
            'contact' => ['required', 'string', 'max:32'],
            'email' => $emailRules,
        ]);

        $accountService->updateIdentity($user, $validated);

        return redirect()->route('profile')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request, UserAccountService $accountService): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $accountService->updatePassword($request->user(), $validated['password']);

        return redirect()->route('profile')->with('status', 'password-updated');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
