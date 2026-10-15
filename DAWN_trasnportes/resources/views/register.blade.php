<?php

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
    </head>
    <body>
            @extends('layouts.app')

            <!-- Inicia la sección content que se inyectará en el layout -->
            @section('content')

            <div class="w-full max-w-2xl p-8 md:p-10 rounded-3xl bg-sky-100 shadow-sm mx-4 my-8">
                <h2 class="text-3xl font-semibold text-center text-slate-800 mb-8">
                    Registro
                </h2>
                
                <!-- Mostrar mensajes de error generales (opcional en Laravel, usualmente se manejan por campo) -->
                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-100 p-4 text-center text-sm text-red-700 shadow-sm">
                        Hay errores en el formulario. Por favor, verifícalos.
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
                    <!-- Directiva obligatoria en Laravel para prevenir ataques CSRF -->
                    @csrf

                    <!-- Fila 1: Nombre y Apellido -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="firstName" class="ml-1 text-sm font-medium text-slate-700">
                                Nombre
                            </label>
                            <!-- Se agrega name="first_name" (convención en PHP/DB suele ser snake_case) y old() para retener el valor -->
                            <input
                                id="firstName"
                                name="first_name"
                                type="text"
                                value="{{ old('first_name') }}"
                                placeholder="Ej. Juan"
                                class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                required
                                autofocus
                            />
                            @error('first_name')
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="lastName" class="ml-1 text-sm font-medium text-slate-700">
                                Apellido
                            </label>
                            <!-- Se agrega name="last_name" -->
                            <input
                                id="lastName"
                                name="last_name"
                                type="text"
                                value="{{ old('last_name') }}"
                                placeholder="Ej. Pérez"
                                class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                required
                            />
                            @error('last_name')
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fila 2: DNI y Género -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="dni" class="ml-1 text-sm font-medium text-slate-700">
                                DNI
                            </label>
                            <!-- Se agrega name="dni" -->
                            <input
                                id="dni"
                                name="dni"
                                type="text"
                                value="{{ old('dni') }}"
                                placeholder="Sin puntos ni espacios"
                                class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                required
                            />
                            @error('dni')
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="gender" class="ml-1 text-sm font-medium text-slate-700">
                                Género
                            </label>
                            <!-- Se agrega name="gender" y lógica para retener la opción seleccionada -->
                            <select
                                id="gender"
                                name="gender"
                                class="w-full rounded-xl border-none bg-white p-4 text-slate-800 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                required
                            >
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Seleccione una opción</option>
                                <option value="femenino" {{ old('gender') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="prefiero-no-decirlo" {{ old('gender') == 'prefiero-no-decirlo' ? 'selected' : '' }}>Prefiero no decirlo</option>
                            </select>
                            @error('gender')
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fila 3: Contacto y Correo -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="contact" class="ml-1 text-sm font-medium text-slate-700">
                                Contacto
                            </label>
                            <!-- Se agrega name="phone" o "contact" -->
                            <input
                                id="contact"
                                name="contact"
                                type="tel"
                                value="{{ old('contact') }}"
                                placeholder="Número de teléfono"
                                class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                required
                            />
                            @error('contact')
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="email" class="ml-1 text-sm font-medium text-slate-700">
                                Correo electrónico
                            </label>
                            <!-- Se agrega name="email" -->
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="correo@ejemplo.com"
                                class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                required
                            />
                            @error('email')
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fila 4: Contraseña -->
                    <div class="flex flex-col gap-2">
                        <label for="password" class="ml-1 text-sm font-medium text-slate-700">
                            Contraseña
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Contraseña"
                            class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                            required
                        />
                        @error('password')
                            <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Fila 5: Confirmar Contraseña -->
                    <div class="flex flex-col gap-2">
                        <label for="password_confirmation" class="ml-1 text-sm font-medium text-slate-700">
                            Confirmar Contraseña
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Confirmar contraseña"
                            class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                            required
                        />
                    </div>

                    <!-- Botón de Registro -->
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-sky-500 py-4 text-center font-bold text-white hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                        Registrarse
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>