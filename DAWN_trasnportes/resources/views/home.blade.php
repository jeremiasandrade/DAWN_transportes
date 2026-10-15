<?php

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home principal</title>
    </head>
    <body>
        @extends('layouts.app')

        @section('content')

        <!-- BLOQUE PHP TEMPORAL PARA PRUEBAS (Hardcodeo de datos) -->
        @php
            $offers = [
                (object) [
                    'id' => 1,
                    'destination' => 'Posadas',
                    'route' => 'Leandro N. Alem - Posadas',
                    'image' => 'https://images.unsplash.com/photo-1630943092426-bd59e28aa361?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxQb3NhZGFzJTIwTWlzaW9uZXMlMjBBcmdlbnRpbmF8ZW58MXx8fHwxNzgyNDIzNDI5fDA&ixlib=rb-4.1.0&q=80&w=1080',
                    'discount' => '15% OFF',
                    'price' => '$8.500',
                    'busType' => 'Minibús',
                ],
                (object) [
                    'id' => 2,
                    'destination' => 'Córdoba',
                    'route' => 'Leandro N. Alem - Córdoba',
                    'image' => 'https://images.unsplash.com/photo-1610995181977-0283a334a65c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxDb3Jkb2JhJTIwY2l0eSUyMEFyZ2VudGluYXxlbnwxfHx8fDE3ODI0MjM0Mjl8MA&ixlib=rb-4.1.0&q=80&w=1080',
                    'discount' => '20% OFF',
                    'price' => '$45.000',
                    'busType' => 'Ómnibus de doble piso',
                ],
                (object) [
                    'id' => 3,
                    'destination' => 'Buenos Aires',
                    'route' => 'Leandro N. Alem - Buenos Aires',
                    'image' => 'https://images.unsplash.com/photo-1589909202802-8f4aadce1849?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxCdWVub3MlMjBBaXJlcyUyMG9iZWxpc2NvfGVufDF8fHx8MTc4MjQyMzQyOXww&ixlib=rb-4.1.0&q=80&w=1080',
                    'discount' => '10% OFF',
                    'price' => '$55.000',
                    'busType' => 'Piso elevado',
                ]
            ];
        @endphp
        <!-- FIN DEL BLOQUE TEMPORAL -->

        <div class="w-full max-w-6xl mx-auto flex flex-col gap-12 pb-12">
            
            <!-- Sección del Buscador (Cabecera Principal) -->
            <section class="w-full">
                <div class="w-full p-6 md:p-8 rounded-3xl bg-sky-100 shadow-sm relative overflow-hidden">
                    <!-- Formulario modificado para GET (típico en buscadores). Debes crear la ruta 'search' o ajustar la URL -->
                    <form method="GET" action="/search" class="flex flex-col gap-6 relative z-10">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            
                            <!-- Origen -->
                            <div class="flex flex-col gap-2">
                                <label for="origin" class="ml-1 text-sm font-medium text-slate-700">
                                    Origen
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG MapPin -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <select
                                        id="origin"
                                        name="origin"
                                        class="w-full appearance-none rounded-xl border-none bg-white p-4 pl-12 pr-10 text-slate-800 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400 cursor-pointer"
                                        required
                                    >
                                        <option value="Leandro N. Alem" {{ request('origin') == 'Leandro N. Alem' ? 'selected' : '' }}>Leandro N. Alem</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG ChevronDown -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Destino -->
                            <div class="flex flex-col gap-2">
                                <label for="destination" class="ml-1 text-sm font-medium text-slate-700">
                                    Destino
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG MapPin -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <select
                                        id="destination"
                                        name="destination"
                                        class="w-full appearance-none rounded-xl border-none bg-white p-4 pl-12 pr-10 text-slate-800 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400 cursor-pointer"
                                        required
                                    >
                                        <option value="" disabled {{ request('destination') ? '' : 'selected' }}>Capital provincial</option>
                                        <option value="Posadas" {{ request('destination') == 'Posadas' ? 'selected' : '' }}>Posadas, Misiones</option>
                                        <option value="Corrientes" {{ request('destination') == 'Corrientes' ? 'selected' : '' }}>Corrientes, Corrientes</option>
                                        <option value="Resistencia" {{ request('destination') == 'Resistencia' ? 'selected' : '' }}>Resistencia, Chaco</option>
                                        <option value="Formosa" {{ request('destination') == 'Formosa' ? 'selected' : '' }}>Formosa, Formosa</option>
                                        <option value="Córdoba" {{ request('destination') == 'Córdoba' ? 'selected' : '' }}>Córdoba, Córdoba</option>
                                        <option value="Buenos Aires" {{ request('destination') == 'Buenos Aires' ? 'selected' : '' }}>Buenos Aires (CABA)</option>
                                        <option value="Mendoza" {{ request('destination') == 'Mendoza' ? 'selected' : '' }}>Mendoza, Mendoza</option>
                                        <option value="Salta" {{ request('destination') == 'Salta' ? 'selected' : '' }}>Salta, Salta</option>
                                        <option value="San Miguel de Tucumán" {{ request('destination') == 'San Miguel de Tucumán' ? 'selected' : '' }}>Tucumán</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG ChevronDown -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div class="flex flex-col gap-2">
                                <label for="date" class="ml-1 text-sm font-medium text-slate-700">
                                    Fecha de viaje
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG Calendar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    </div>
                                    <input
                                        id="date"
                                        name="date"
                                        type="date"
                                        value="{{ request('date') }}"
                                        class="w-full rounded-xl border-none bg-white p-4 pl-12 text-slate-800 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- Tipo de Vehículo -->
                            <div class="flex flex-col gap-2">
                                <label for="vehicle" class="ml-1 text-sm font-medium text-slate-700">
                                    Vehículo requerido
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG Bus -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg>
                                    </div>
                                    <select
                                        id="vehicle"
                                        name="vehicle"
                                        class="w-full appearance-none rounded-xl border-none bg-white p-4 pl-12 pr-10 text-slate-800 shadow-sm outline-none ring-2 ring-transparent transition-all focus:ring-sky-400 cursor-pointer"
                                        required
                                    >
                                        <option value="" disabled {{ request('vehicle') ? '' : 'selected' }}>Seleccionar tipo</option>
                                        <option value="doble-piso" {{ request('vehicle') == 'doble-piso' ? 'selected' : '' }}>Ómnibus de doble piso</option>
                                        <option value="piso-elevado" {{ request('vehicle') == 'piso-elevado' ? 'selected' : '' }}>Piso elevado</option>
                                        <option value="minibus" {{ request('vehicle') == 'minibus' ? 'selected' : '' }}>Minibús</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                        <!-- SVG ChevronDown -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Botón de búsqueda -->
                        <div class="flex justify-end mt-2">
                            <button
                                type="submit"
                                class="w-full lg:w-auto px-10 py-4 rounded-xl bg-sky-600 font-bold text-white shadow-md transition-colors hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-300 flex items-center justify-center gap-3 text-lg tracking-wide"
                            >
                                <!-- SVG Search -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                                Buscar Viajes disponibles
                            </button>
                        </div>

                    </form>
                </div>
            </section>

            <!-- Sección de Ofertas y Destinos Destacados -->
            <section>
                <div class="flex items-center gap-3 mb-8">
                    <!-- SVG Tag -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-600"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800">
                        Ofertas y Destinos Destacados
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Iteración sobre el array $offers usando Blade -->
                    @foreach ($offers as $offer)
                        <article class="rounded-3xl bg-sky-100 shadow-sm flex flex-col overflow-hidden transition-all hover:-translate-y-2 hover:shadow-lg">
                            <!-- Imagen del destino -->
                            <div class="h-56 w-full relative">
                                <img 
                                    src="{{ $offer->image }}" 
                                    alt="Destino {{ $offer->destination }}" 
                                    class="h-full w-full object-cover"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            </div>

                            <!-- Contenido de la tarjeta -->
                            <div class="p-6 flex flex-col flex-1">
                                
                                <!-- Ruta -->
                                <h3 class="text-xl font-extrabold text-slate-800 mb-4">
                                    {{ $offer->route }}
                                </h3>
                                
                                <!-- Etiqueta de descuento y Precio -->
                                <div class="flex items-center justify-between bg-white/60 p-3 rounded-2xl mb-4">
                                    <span class="rounded-lg bg-green-100 px-3 py-1.5 text-sm font-extrabold tracking-wide text-green-700 shadow-sm">
                                        {{ $offer->discount }}
                                    </span>
                                    <div class="flex flex-col items-end">
                                        <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Precio final</span>
                                        <span class="text-2xl font-black text-sky-700">{{ $offer->price }}</span>
                                    </div>
                                </div>

                                <!-- Tipo de vehículo -->
                                <div class="flex items-center gap-2 text-slate-600 mb-6 bg-white/40 px-3 py-2 rounded-xl w-fit">
                                    <!-- SVG Bus -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-600"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg>
                                    <span class="text-sm font-semibold">{{ $offer->busType }}</span>
                                </div>
                                
                                <!-- Botón de llamado a la acción -->
                                <div class="mt-auto">
                                    <!-- Enlace modificado para usar rutas de Laravel -->
                                    <a 
                                        href="/destination/{{ $offer->id }}"
                                        class="block w-full rounded-xl bg-sky-600 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-sky-700 hover:shadow-lg active:scale-[0.98]"
                                    >
                                        Reservar pasaje
                                    </a>
                                </div>
                                
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

        </div>

        @endsection
            
        </body>
</html>