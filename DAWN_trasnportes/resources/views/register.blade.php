@extends('layouts.app')

@section('content')
    <section class="mx-4 w-full max-w-132 rounded-[20px] border border-sky-200/50 bg-sky-100/80 p-8 shadow-md">
        <h1 class="mb-6 text-center text-2xl font-bold text-slate-800">
            Registro
        </h1>

        <form method="POST" action="{{ route('register.store') }}" class="grid grid-cols-1 gap-x-5 gap-y-4 md:grid-cols-2">
            @csrf

            @if ($errors->any())
                <div class="col-span-full rounded-xl bg-red-100 p-3 text-center text-sm text-red-700 shadow-sm">
                    Hay errores en el formulario. Por favor, verifícalos.
                </div>
            @endif

            <div class="flex flex-col gap-1.5">
                <label for="firstName" class="ml-1 text-xs font-semibold text-slate-700">Nombre</label>
                <input
                    id="firstName"
                    name="first_name"
                    type="text"
                    value="{{ old('first_name') }}"
                    placeholder="Ej. Juan"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                    autofocus
                />
                @error('first_name')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="lastName" class="ml-1 text-xs font-semibold text-slate-700">Apellido</label>
                <input
                    id="lastName"
                    name="last_name"
                    type="text"
                    value="{{ old('last_name') }}"
                    placeholder="Ej. Pérez"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                />
                @error('last_name')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="dni" class="ml-1 text-xs font-semibold text-slate-700">DNI</label>
                <input
                    id="dni"
                    name="dni"
                    type="text"
                    value="{{ old('dni') }}"
                    placeholder="Sin puntos ni espacios"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                />
                @error('dni')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="gender" class="ml-1 text-xs font-semibold text-slate-700">Género</label>
                <select
                    id="gender"
                    name="gender"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-700 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                >
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Seleccione una opción</option>
                    <option value="femenino" {{ old('gender') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="prefiero-no-decirlo" {{ old('gender') == 'prefiero-no-decirlo' ? 'selected' : '' }}>Prefiero no decirlo</option>
                </select>
                @error('gender')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="contact" class="ml-1 text-xs font-semibold text-slate-700">Contacto</label>
                <input
                    id="contact"
                    name="contact"
                    type="tel"
                    value="{{ old('contact') }}"
                    placeholder="Número de teléfono"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                />
                @error('contact')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="email" class="ml-1 text-xs font-semibold text-slate-700">Correo electrónico</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.com"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                />
                @error('email')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password" class="ml-1 text-xs font-semibold text-slate-700">Contraseña</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                />
                @error('password')
                    <span class="ml-1 text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="ml-1 text-xs font-semibold text-slate-700">Confirmar contraseña</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                    required
                />
            </div>

            <button
                type="submit"
                class="col-span-full mt-1 w-full rounded-xl bg-[#0284c7] py-3 text-center text-sm font-semibold text-white shadow-md transition-all hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300 active:scale-[0.98]"
            >
                Registrarse
            </button>

            <p class="col-span-full text-center text-xs text-slate-600">
                ¿ya tiene una cuenta?
                <a href="{{ route('login') }}" class="font-semibold text-sky-700 hover:underline">iniciar sesión</a>
            </p>
        </form>
    </section>
@endsection