<?php

namespace App\Http\Controllers;

use App\Services\UserAccountService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('register');
    }

    public function store(Request $request, UserAccountService $accountService): RedirectResponse
    {
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

        $user = $accountService->createCustomer($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
