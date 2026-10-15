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

    <div class="w-full max-w-md p-8 md:p-10 rounded-3xl bg-sky-100 shadow-sm mx-4">
        <h2 class="text-3xl font-semibold text-center text-slate-800 mb-8">
            Bienvenido
        </h2>
    
        <!-- Se reemplaza onSubmit de React por action y method="POST" para Laravel -->
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
            <!-- Directiva obligatoria en Laravel para prevenir ataques CSRF -->
            @csrf

            <div class="flex flex-col gap-2">
                <label for="username" class="text-sm font-medium text-slate-700 ml-1">
                    Usuario
                </label>
                <!-- Se agrega el atributo name="username" para que PHP capture el dato -->
                <!-- Se usa value="{{ old('username') }}" para no perder el usuario si hay un error de validación -->
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    placeholder="Ingresa tu usuario"
                    class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                    required
                    autofocus
                />
                <!-- Opcional: Mostrar mensaje de error si la validación en el controlador falla -->
                @error('username')
                    <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-sm font-medium text-slate-700 ml-1">
                    Contraseña
                </label>
                <!-- Se agrega el atributo name="password" -->
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border-none bg-white p-4 text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                    required
                />
                @error('password')
                    <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4 flex flex-col gap-4">
                <button
                    type="submit"
                    class="w-full rounded-xl bg-sky-600 p-4 text-center font-semibold text-white shadow-md transition-colors hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-300"
                >
                    Iniciar sesión
                </button>
                <p class="text-center text-sm text-slate-700">
                    ¿no tiene una cuenta? 
                    <!-- Se reemplaza el componente <Link> de React Router por una etiqueta <a> con la ruta de Laravel -->
                    <a href="{{ route('register') }}" class="font-semibold text-sky-800 hover:underline">
                        registrese
                    </a>
                </p>
            </div>
        </form>
    </div>

    @endsection
</body>

</html>