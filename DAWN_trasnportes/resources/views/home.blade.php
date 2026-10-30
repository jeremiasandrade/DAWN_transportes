@extends('layouts.app')

@section('content')
    @php
        $offers = [
            ['id' => 1, 'route' => 'Leandro N. Alem - Posadas', 'discount' => '15% OFF', 'price' => '$8.500', 'vehicle' => 'Minibús'],
            ['id' => 2, 'route' => 'Leandro N. Alem - Córdoba', 'discount' => '20% OFF', 'price' => '$45.000', 'vehicle' => 'Ómnibus de doble piso'],
            ['id' => 3, 'route' => 'Leandro N. Alem - Buenos Aires', 'discount' => '10% OFF', 'price' => '$55.000', 'vehicle' => 'Piso elevado'],
            ['id' => 4, 'route' => 'Leandro N. Alem - Corrientes', 'discount' => '12% OFF', 'price' => '$12.000', 'vehicle' => 'Minibús'],
            ['id' => 5, 'route' => 'Leandro N. Alem - Resistencia', 'discount' => '10% OFF', 'price' => '$18.500', 'vehicle' => 'Ómnibus de doble piso'],
            ['id' => 6, 'route' => 'Leandro N. Alem - Formosa', 'discount' => '8% OFF', 'price' => '$22.000', 'vehicle' => 'Piso elevado'],
            ['id' => 7, 'route' => 'Leandro N. Alem - Tucumán', 'discount' => '15% OFF', 'price' => '$39.000', 'vehicle' => 'Ómnibus de doble piso'],
            ['id' => 8, 'route' => 'Leandro N. Alem - Salta', 'discount' => '10% OFF', 'price' => '$46.000', 'vehicle' => 'Piso elevado'],
            ['id' => 9, 'route' => 'Leandro N. Alem - Mendoza', 'discount' => '18% OFF', 'price' => '$58.000', 'vehicle' => 'Ómnibus de doble piso'],
        ];
        $offerPages = array_chunk($offers, 3);
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
                                <option value="">Seleccionar origen</option>
                                @foreach($ciudades->groupBy(fn ($ciudad) => $ciudad->provincia?->nombre_provincia ?? 'Sin provincia') as $provincia => $grupo)
                                    <optgroup label="{{ $provincia }}">
                                        @foreach($grupo as $ciudad)
                                            <option value="{{ $ciudad->id_ciudad }}" @selected($origenSeleccionado?->id_ciudad === $ciudad->id_ciudad)>
                                                {{ $ciudad->nombre_ciudad }}@if($ciudad->departamento) — {{ $ciudad->departamento }}@endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <svg aria-hidden="true" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>

                    <div class="flex min-w-0 flex-col gap-1.5">
                        <label for="destination" class="ml-1 text-xs font-semibold text-slate-600">Destino</label>
                        <div class="relative">
                            <svg aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <select id="destination" name="destination" class="w-full appearance-none rounded-xl border border-sky-100 bg-white py-3.5 pl-10 pr-9 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="">Seleccionar destino</option>
                                @foreach($ciudades->groupBy(fn ($ciudad) => $ciudad->provincia?->nombre_provincia ?? 'Sin provincia') as $provincia => $grupo)
                                    <optgroup label="{{ $provincia }}">
                                        @foreach($grupo as $ciudad)
                                            <option value="{{ $ciudad->id_ciudad }}" @selected($destinoSeleccionado?->id_ciudad === $ciudad->id_ciudad)>
                                                {{ $ciudad->nombre_ciudad }}@if($ciudad->departamento) — {{ $ciudad->departamento }}@endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
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

            @if($busquedaRealizada)
                <div class="mt-5 rounded-xl border border-sky-200 bg-white p-4 shadow-sm">
                    @if($mensajeDistancia)
                        <p class="text-sm font-semibold text-amber-700">{{ $mensajeDistancia }}</p>
                    @else
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase text-slate-400">Recorrido seleccionado</p>
                                <p class="text-base font-extrabold text-slate-800">
                                    {{ $origenSeleccionado->nombre_ciudad }} → {{ $destinoSeleccionado->nombre_ciudad }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $origenSeleccionado->provincia?->nombre_provincia }} → {{ $destinoSeleccionado->provincia?->nombre_provincia }}
                                </p>
                            </div>
                            <div class="rounded-xl bg-sky-100 px-4 py-2 text-right">
                                <p class="text-[10px] font-bold uppercase text-sky-700">Distancia real</p>
                                <p class="text-2xl font-black text-sky-700">{{ number_format($distancia, 0, ',', '.') }} km</p>
                            </div>
                        </div>
                        @if($rutaExistente)
                            <p class="mt-3 text-xs font-semibold text-green-700">Ruta registrada en el sistema.</p>
                        @endif
                    @endif
                </div>
            @endif
        </section>

        <section>
            <div class="mb-5 flex items-center gap-3">
                <svg aria-hidden="true" class="h-6 w-6 shrink-0 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3H4a1 1 0 0 0-1 1v5.59A2 2 0 0 0 3.59 11l9.58 9.59a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.59Z"/><circle cx="7" cy="7" r="1"/></svg>
                <h1 class="text-xl font-bold text-slate-800 md:text-2xl">Ofertas y Destinos Destacados</h1>
            </div>

            <div data-trip-carousel aria-roledescription="carrusel" aria-label="Viajes destacados">
                <div data-carousel-pages>
                    @foreach ($offerPages as $pageIndex => $pageOffers)
                        <div data-carousel-page="{{ $pageIndex }}" class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($pageOffers as $offer)
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
                    @endforeach
                </div>

                <div class="mt-5 flex items-center justify-between gap-4" aria-label="Controles de viajes destacados">
                    <button type="button" data-carousel-previous class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-400" aria-label="Página anterior">
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <div class="flex items-center justify-center gap-2" role="group" aria-label="Seleccionar página">
                        @foreach ($offerPages as $pageIndex => $pageOffers)
                            <button type="button" data-carousel-indicator="{{ $pageIndex }}" class="h-2.5 w-2.5 rounded-full bg-slate-300 transition-colors hover:bg-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-2" aria-label="Mostrar página {{ $pageIndex + 1 }}" aria-current="{{ $pageIndex === 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                    <button type="button" data-carousel-next class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-400" aria-label="Página siguiente">
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <script>
        (() => {
            const carousel = document.querySelector('[data-trip-carousel]');

            if (!carousel) {
                return;
            }

            const pages = Array.from(carousel.querySelectorAll('[data-carousel-page]'));
            const indicators = Array.from(carousel.querySelectorAll('[data-carousel-indicator]'));
            let activePage = 0;
            let rotationTimer;

            const showPage = (pageIndex) => {
                activePage = (pageIndex + pages.length) % pages.length;

                pages.forEach((page, index) => {
                    page.classList.toggle('hidden', index !== activePage);
                });

                indicators.forEach((indicator, index) => {
                    const isActive = index === activePage;
                    indicator.setAttribute('aria-current', String(isActive));
                    indicator.classList.toggle('bg-sky-600', isActive);
                    indicator.classList.toggle('bg-slate-300', !isActive);
                });
            };

            const startRotation = () => {
                window.clearInterval(rotationTimer);
                rotationTimer = window.setInterval(() => showPage(activePage + 1), 6000);
            };

            carousel.querySelector('[data-carousel-previous]').addEventListener('click', () => {
                showPage(activePage - 1);
                startRotation();
            });

            carousel.querySelector('[data-carousel-next]').addEventListener('click', () => {
                showPage(activePage + 1);
                startRotation();
            });

            indicators.forEach((indicator) => {
                indicator.addEventListener('click', () => {
                    showPage(Number(indicator.dataset.carouselIndicator));
                    startRotation();
                });
            });

            showPage(0);
            startRotation();
        })();
    </script>
@endsection
