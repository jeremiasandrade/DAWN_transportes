<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dawn Transportes</title>
    <!-- Directiva de Laravel para cargar Tailwind CSS a través de Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans">

    @include('partials.header')

    <main class="flex-grow flex items-center justify-center py-10">
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>