@extends('layouts.app')

@section('content')
    @php
        $offers = [
            ['id' => 1, 'route' => 'Leandro N. Alem - Posadas', 'discount' => '15% OFF', 'price' => '$8.500', 'vehicle' => 'Minibús'],
            ['id' => 2, 'route' => 'Leandro N. Alem - Córdoba', 'discount' => '20% OFF', 'price' => '$45.000', 'vehicle' => 'Ómnibus de doble piso'],
            ['id' => 3, 'route' => 'Leandro N. Alem - Buenos Aires', 'discount' => '10% OFF', 'price' => '$55.000', 'vehicle' => 'Piso elevado'],
        ];
    @endphp

    <div class="mx-auto flex w-[calc(100%-2rem)] flex-col gap-10 pb-8 md:w-[85%] md:max-w-6xl">
        <section class="rounded-[20px] border border-sky-200/50 bg-sky-100/80 p-5 shadow-sm md:p-6">
            <form method="GET" action="{{ route('home') }}" class="flex flex-col gap-5">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="flex min-w-0 flex-col gap-1.5">
                        <label for="origin" class="ml-1 text-xs font-semibold text-slate-600">Origen</label>
                        <div class="relative">
                            <svg aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <select id="origin" name="origin" class="w-full appearance-none rounded-xl border border-sky-100 bg-white py-3.5 pl-10 pr-9 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="Leandro N. Alem" selected>Leandro N. Alem</option>
                            </select>
                            <svg aria-hidden="true" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    <div class="flex min-w-0 flex-col gap-1.5">
                        <label for="destination" class="ml-1 text-xs font-semibold text-slate-600">Destino</label>
                        <div class="relative">
                            <svg aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <select id="destination" name="destination" class="w-full appearance-none rounded-xl border border-sky-100 bg-white py-3.5 pl-10 pr-9 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="" disabled selected>Capital provincial</option>
                                <option value="Posadas">Posadas, Misiones</option>
                                <option value="Corrientes">Corrientes, Corrientes</option>
                                <option value="Resistencia">Resistencia, Chaco</option>
                                <option value="Formosa">Formosa, Formosa</option>
                                <option value="Córdoba">Córdoba, Córdoba</option>
                                <option value="Buenos Aires">Buenos Aires (CABA)</option>
                                <option value="Mendoza">Mendoza, Mendoza</option>
                                <option value="Salta">Salta, Salta</option>
                                <option value="San Miguel de Tucumán">Tucumán</option>
                            </select>
                            <svg aria-hidden="true" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    <div class="flex min-w-0 flex-col gap-1.5">
                        <label for="date" class="ml-1 text-xs font-semibold text-slate-600">Fecha de viaje</label>
                        <div class="relative">
                            <svg aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <input id="date" name="date" type="date" class="w-full rounded-xl border border-sky-100 bg-white py-3.5 pl-10 pr-3 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400" />
                        </div>
                    </div>

                    <div class="flex min-w-0 flex-col gap-1.5">
                        <label for="vehicle" class="ml-1 text-xs font-semibold text-slate-600">Vehículo requerido</label>
                        <div class="relative">
                            <svg aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6M15 6v6M2 12h19.6M18 18h3l.8-2.8c.1-.4.2-.8.2-1.2s-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg>
                            <select id="vehicle" name="vehicle" class="w-full appearance-none rounded-xl border border-sky-100 bg-white py-3.5 pl-10 pr-9 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="" disabled selected>Seleccionar tipo</option>
                                <option value="minibus">Minibús</option>
                                <option value="doble-piso">Ómnibus de doble piso</option>
                                <option value="piso-elevado">Piso elevado</option>
                            </select>
                            <svg aria-hidden="true" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0284c7] px-6 py-3.5 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300 sm:w-69.5">
                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        Buscar Viajes disponibles
                    </button>
                </div>
            </form>
        </section>

        <section>
            <div class="mb-5 flex items-center gap-3">
                <svg aria-hidden="true" class="h-6 w-6 shrink-0 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3H4a1 1 0 0 0-1 1v5.59A2 2 0 0 0 3.59 11l9.58 9.59a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.59Z"/><circle cx="7" cy="7" r="1"/></svg>
                <h1 class="text-xl font-bold text-slate-800 md:text-2xl">Ofertas y Destinos Destacados</h1>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($offers as $offer)
                    <article class="flex min-w-0 flex-col overflow-hidden rounded-[20px] border border-sky-200/50 bg-sky-100/80 shadow-sm">
                        <div class="h-44 shrink-0 bg-slate-200/70" aria-hidden="true"></div>
                        <div class="flex flex-1 flex-col p-4">
                            <h2 class="mb-3 text-base font-extrabold text-slate-800">{{ $offer['route'] }}</h2>
                            <div class="mb-3 flex items-center justify-between gap-2 rounded-2xl bg-white/70 p-2.5">
                                <span class="rounded-lg bg-green-100 px-2.5 py-1 text-xs font-extrabold text-green-700 shadow-sm">{{ $offer['discount'] }}</span>
                                <div class="flex flex-col items-end">
                                    <span class="text-[10px] font-medium uppercase text-slate-500">Precio final</span>
                                    <span class="text-xl font-black text-sky-700">{{ $offer['price'] }}</span>
                                </div>
                            </div>
                            <div class="mb-4 flex w-fit items-center gap-1.5 rounded-xl bg-white/50 px-2.5 py-1.5 text-xs font-semibold text-slate-600">
                                <svg aria-hidden="true" class="h-4 w-4 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6M15 6v6M2 12h19.6M18 18h3l.8-2.8c.1-.4.2-.8.2-1.2s-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg>
                                {{ $offer['vehicle'] }}
                            </div>
                            <a href="{{ route('viajes.show', $offer['id']) }}" class="mt-auto block w-full rounded-xl bg-[#0284c7] py-3 text-center text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300">Reservar pasaje</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
