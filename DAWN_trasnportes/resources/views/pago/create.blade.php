@extends('layouts.app')

@section('title', 'Confirmar compra')

@section('content')
    @php
        $nombreSugerido = trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: ($user->name ?? '');
        $metodoSeleccionado = old('metodo_pago', 'mercado_pago');
    @endphp

    <div class="mx-auto w-[calc(100%-2rem)] max-w-5xl pb-8">
        <a href="{{ route('viajes.show', $viaje['id']) }}" class="mb-5 inline-flex items-center gap-2 rounded-full bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition-colors hover:bg-sky-200">
            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver al viaje
        </a>

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
            <section class="rounded-[22px] border border-sky-200/60 bg-sky-100/80 p-5 shadow-sm sm:p-7">
                <p class="text-[10px] font-bold uppercase text-sky-700">Checkout</p>
                <h1 class="mt-1 text-3xl font-extrabold text-slate-800">Finalizá tu compra</h1>
                <p class="mt-2 text-sm leading-5 text-slate-600">Revisá los datos del pasajero y elegí cómo querés pagar.</p>

                @if($errors->any())
                    <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        Revisá los datos marcados antes de confirmar la compra.
                    </div>
                @endif

                <form action="{{ route('pagos.store') }}" method="POST" class="mt-6 flex flex-col gap-6">
                    @csrf
                    <input type="hidden" name="viaje_id" value="{{ $viaje['id'] }}">

                    <div>
                        <div class="mb-3 flex items-center gap-2">
                            <svg aria-hidden="true" class="h-5 w-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <h2 class="text-base font-bold text-slate-800">Datos del pasajero</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="flex flex-col gap-1.5">
                                <label for="nombre_pasajero" class="ml-1 text-xs font-semibold text-slate-600">Nombre y apellido</label>
                                <input id="nombre_pasajero" name="nombre_pasajero" type="text" value="{{ old('nombre_pasajero', $nombreSugerido) }}" required class="w-full rounded-xl border border-sky-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400" />
                                @error('nombre_pasajero')<p class="ml-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="dni_pasajero" class="ml-1 text-xs font-semibold text-slate-600">DNI</label>
                                <input id="dni_pasajero" name="dni_pasajero" type="text" value="{{ old('dni_pasajero', $user->dni) }}" required class="w-full rounded-xl border border-sky-100 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none focus:ring-2 focus:ring-sky-400" />
                                @error('dni_pasajero')<p class="ml-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-sky-200/70 pt-5">
                        <div class="mb-3 flex items-center gap-2">
                            <svg aria-hidden="true" class="h-5 w-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
                            <h2 class="text-base font-bold text-slate-800">Método de pago</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                            @foreach($metodosPago as $valor => $metodo)
                                <label class="cursor-pointer">
                                    <input type="radio" name="metodo_pago" value="{{ $valor }}" class="peer sr-only" @checked($metodoSeleccionado === $valor)>
                                    <span class="flex h-full items-start gap-3 rounded-xl border border-sky-100 bg-white p-3.5 shadow-sm transition-colors peer-checked:border-sky-500 peer-checked:bg-sky-50 peer-checked:ring-2 peer-checked:ring-sky-200">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                                            @if($valor === 'mercado_pago')
                                                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                                            @elseif($valor === 'transferencia')
                                                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 10V21"/><path d="M19 10V21"/><path d="m12 3 9 7H3l9-7Z"/></svg>
                                            @else
                                                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/><path d="M6 15h2"/></svg>
                                            @endif
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-sm font-bold text-slate-800">{{ $metodo['etiqueta'] }}</span>
                                            <span class="mt-0.5 block text-[11px] leading-4 text-slate-500">{{ $metodo['detalle'] }}</span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('metodo_pago')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-[11px] leading-5 text-amber-800">
                        Esta es una pasarela de demostración: no se realizan cobros reales. Al confirmar, te enviaremos el pasaje por correo y el comprobante quedará registrado en tu cuenta.
                    </div>

                    <div class="flex flex-col-reverse items-stretch justify-between gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('viajes.show', $viaje['id']) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">Cancelar</a>
                        <button type="submit" class="rounded-xl bg-[#0284c7] px-6 py-3 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300">
                            Confirmar pago
                        </button>
                    </div>
                </form>
            </section>

            <aside class="rounded-[22px] border border-sky-200/60 bg-white p-5 shadow-sm lg:sticky lg:top-5">
                <p class="text-[10px] font-bold uppercase text-slate-400">Resumen del viaje</p>
                <h2 class="mt-1 text-xl font-extrabold text-slate-800">{{ $viaje['origin'] }} → {{ $viaje['destination'] }}</h2>
                <p class="mt-1 text-xs text-slate-500">{{ $viaje['company'] }}</p>

                <div class="mt-4 rounded-xl bg-slate-900 p-4 text-white">
                    <p class="text-[10px] font-bold uppercase text-sky-200">Total a pagar</p>
                    <p class="text-3xl font-black">{{ $viaje['price'] }}</p>
                </div>

                <dl class="mt-4 flex flex-col gap-2.5 text-sm">
                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2.5">
                        <dt class="text-slate-500">Vehículo</dt>
                        <dd class="text-right font-bold text-slate-700">{{ $viaje['vehicle'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2.5">
                        <dt class="text-slate-500">Distancia</dt>
                        <dd class="text-right font-bold text-slate-700">{{ $viaje['distance'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2.5">
                        <dt class="text-slate-500">Partida</dt>
                        <dd class="text-right font-bold text-slate-700">{{ $viaje['departure'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-500">Llegada</dt>
                        <dd class="text-right font-bold text-slate-700">{{ $viaje['arrival'] }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </div>
@endsection
