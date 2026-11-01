<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dawn Transportes</title>
    <script>
        try {
            if (localStorage.getItem('dawn-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch (error) {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <!-- Directiva de Laravel para cargar Tailwind CSS a través de Vite -->
    @vite('resources/css/app.css')
</head>
<body class="{{ request()->routeIs('profile') ? 'bg-[#f7f8f5]' : (request()->routeIs('login', 'register', 'home', 'viajes.show', 'pagos.*') ? 'bg-[#e6e7e8]' : 'bg-slate-50') }} min-h-screen flex flex-col font-sans">

    @include('partials.header')

    @auth
        @can('access-development')
            <nav class="border-b border-amber-200 bg-amber-50" aria-label="Navegación administrativa">
                <div class="mx-auto flex w-[calc(100%-2rem)] max-w-7xl items-center gap-5 py-2 text-sm">
                    <a href="{{ route('desarrollo') }}" class="font-semibold text-amber-900 hover:underline">Panel Admin</a>
                    <a href="{{ route('admin.viajes-recomendados.index') }}" class="text-amber-800 hover:underline">Viajes recomendados</a>
                </div>
            </nav>
        @endcan
    @endauth

    <main class="grow flex {{ request()->routeIs('home', 'viajes.show', 'profile', 'pagos.*', 'desarrollo') ? 'items-start justify-center py-4 md:py-8' : 'items-center justify-center py-10' }} {{ request()->routeIs('login', 'register') ? 'min-h-[calc(100vh-62px)]' : '' }}">
        @yield('content')
    </main>

    @unless (request()->routeIs('login', 'register', 'home', 'viajes.show', 'pagos.*', 'desarrollo'))
        @include('partials.footer')
    @endunless

    <script>
        (() => {
            const themeToggle = document.querySelector('[data-theme-toggle]');

            if (!themeToggle) {
                return;
            }

            const updateThemeControl = () => {
                const isDark = document.documentElement.classList.contains('dark');
                const label = isDark ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro';

                themeToggle.setAttribute('aria-label', label);
                themeToggle.setAttribute('title', label);
                themeToggle.querySelector('[data-theme-icon="moon"]').classList.toggle('hidden', isDark);
                themeToggle.querySelector('[data-theme-icon="sun"]').classList.toggle('hidden', !isDark);
            };

            updateThemeControl();

            themeToggle.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');

                try {
                    localStorage.setItem('dawn-theme', isDark ? 'dark' : 'light');
                } catch (error) {
                    // The current page still changes theme when storage is unavailable.
                }

                updateThemeControl();
            });
        })();
    </script>
</body>
</html>
