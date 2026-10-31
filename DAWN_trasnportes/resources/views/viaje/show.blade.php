@extends('layouts.app')

@section('content')
    <div class="mx-auto w-[calc(100%-2rem)] max-w-179.5 pb-8">
        <a href="{{ route('home') }}" class="mb-5 inline-flex items-center gap-2 rounded-full bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-200">
            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver a destinos
        </a>

        <article class="overflow-hidden rounded-[22px] border border-sky-200/60 bg-sky-100/80 shadow-sm">
            <div class="h-44 bg-slate-200/70 sm:h-56" aria-hidden="true"></div>

            <div class="p-5 sm:p-7">
                <div class="trip-detail-summary grid grid-cols-1 gap-5 sm:items-center">
                    <div class="hidden h-28 w-28 rounded-xl border-2 border-white bg-slate-200/70 shadow-md sm:block" aria-hidden="true"></div>

                    <div class="min-w-0">
                        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">Viaje a {{ $trip['destination'] }}</h1>
                        <p class="mt-2 text-sm leading-5 text-slate-600">{{ $trip['description'] }}</p>
                    </div>

                    <div class="flex flex-row items-center justify-between gap-3 rounded-xl border border-sky-100 bg-white p-3 sm:flex-col sm:justify-center">
                        <div class="text-left sm:text-center">
                            <p class="text-[10px] font-semibold uppercase text-slate-400">Precio total</p>
                            <p class="text-2xl font-black text-sky-700">{{ $trip['price'] }}</p>
                        </div>
                        <a href="{{ route('pagos.create', ['viaje_id' => $trip['id']]) }}" class="shrink-0 whitespace-nowrap rounded-xl bg-[#0284c7] px-3 py-3 text-center text-xs font-bold text-white shadow-md transition-colors hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300">Comprar pasaje</a>
                    </div>
                </div>

                <div class="my-6 border-t border-sky-200/70"></div>

                <div class="mb-4 flex items-center gap-2">
                    <svg aria-hidden="true" class="h-5 w-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></svg>
                    <h2 class="text-lg font-bold text-slate-800">Ficha Técnica del Viaje</h2>
                </div>

                <dl class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="flex min-w-0 items-center gap-2.5 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-500">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 17v-6m3 6V7m3 10v-4"/></svg>
                        </span>
                        <div class="min-w-0"><dt class="text-[9px] font-bold uppercase text-slate-400">Empresa</dt><dd class="truncate text-xs font-bold text-slate-700">{{ $trip['company'] }}</dd></div>
                    </div>

                    <div class="flex min-w-0 items-center gap-2.5 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-500">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6m7-6v6M2 12h19.6M18 18h3l.8-2.8c.1-.4.2-.8.2-1.2s-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg>
                        </span>
                        <div class="min-w-0"><dt class="text-[9px] font-bold uppercase text-slate-400">Tipo de bus</dt><dd class="truncate text-xs font-bold text-slate-700">{{ $trip['vehicle'] }}</dd></div>
                    </div>

                    <div class="flex min-w-0 items-center gap-2.5 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-500">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div><dt class="text-[9px] font-bold uppercase text-slate-400">Distancia</dt><dd class="text-xs font-bold text-slate-700">{{ $trip['distance'] }}</dd></div>
                    </div>

                    <div class="flex min-w-0 items-center gap-2.5 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-500">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </span>
                        <div><dt class="text-[9px] font-bold uppercase text-slate-400">Partida</dt><dd class="text-xs font-bold text-slate-700">{{ $trip['departure'] }}</dd></div>
                    </div>

                    <div class="flex min-w-0 items-center gap-2.5 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-500">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </span>
                        <div><dt class="text-[9px] font-bold uppercase text-slate-400">Llegada estimada</dt><dd class="text-xs font-bold text-slate-700">{{ $trip['arrival'] }}</dd></div>
                    </div>

                    <div class="flex min-w-0 items-center gap-2.5 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600">
                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 8v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-8Z"/><path d="M13 5v2m0 4v2m0 4v2"/></svg>
                        </span>
                        <div><dt class="text-[9px] font-bold uppercase text-slate-400">Disponibilidad</dt><dd class="text-xs font-bold text-green-700">{{ $trip['availability'] }}</dd></div>
                    </div>
                </dl>
            </div>
        </article>
    </div>
@endsection
