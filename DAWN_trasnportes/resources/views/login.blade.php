<?php
//estas son las vistas del formulario de inicio de sesión, con la posibilidad de ingresar sesión con el correo y la contraseña o en cuyo caso que no tenga
//una cuenta se puede registrar  en un botón debajo del formulario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>

@extends('layouts.app')

@section('content')
        <div class="w-full max-w-md p-8 md:p-12 rounded-3xl bg-sky-100/80 shadow-lg border border-sky-200/50 mx-4">
            <h2 class="text-3xl font-bold text-center text-slate-800 mb-8 tracking-tight">
                Bienvenido
            </h2>
            
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
                @csrf

                <div class="flex flex-col gap-2">
                    <label for="username" class="text-sm font-semibold text-slate-700 ml-1">
                        Usuario
                    </label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username') }}"
                        placeholder="Ingresa tu usuario"
                        class="w-full rounded-2xl border border-sky-200 bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400 focus:border-transparent"
                        required
                        autofocus
                    />
                    @error('username')
                        <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password" class="text-sm font-semibold text-slate-700 ml-1">
                        Contraseña
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-2xl border border-sky-200 bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400 focus:border-transparent"
                        required
                    />
                    @error('password')
                        <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4 flex flex-col gap-4">
                    <!-- Botón ajustado con el tono de azul exacto y esquinas más redondeadas -->
                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-[#0284c7] p-4 text-center font-semibold text-white shadow-md transition-all hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300 active:scale-[0.98]"
                    >
                        Iniciar sesión
                    </button>
                    <p class="text-center text-sm text-slate-600">
                        ¿no tiene una cuenta? 
                        <a href="{{ route('register') }}" class="font-semibold text-sky-700 hover:underline">
                            registrese
                        </a>
                    </p>
                </div>
            </form>
        </div>
@endsection
</body>

</html>