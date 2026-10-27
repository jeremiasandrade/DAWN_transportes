<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dawn Transportes</title>
    <!-- Directiva de Laravel para cargar Tailwind CSS a través de Vite -->
    @vite('resources/css/app.css')
</head>
<body class="{{ request()->routeIs('login', 'register', 'home', 'viajes.show') ? 'bg-[#e6e7e8]' : 'bg-slate-50' }} min-h-screen flex flex-col font-sans">

    @include('partials.header')

    <main class="grow flex {{ request()->routeIs('home', 'viajes.show') ? 'items-start justify-center py-4' : 'items-center justify-center py-10' }} {{ request()->routeIs('login', 'register') ? 'min-h-[calc(100vh-62px)]' : '' }}">
        @yield('content')
    </main>

    @unless (request()->routeIs('login', 'register', 'home', 'viajes.show'))
        @include('partials.footer')
    @endunless

</body>
</html>