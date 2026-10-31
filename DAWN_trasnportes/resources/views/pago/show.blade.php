@extends('layouts.app')

@section('title', 'Compra confirmada')

@section('content')
    @php
        $pagoAprobado = $pago->estado === 'aprobado';
    @endphp

    <div class="mx-auto w-[calc(100%-2rem)] max-w-3xl pb-8">
        @if (session('success'))
            <div role="status" class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-[22px] border border-sky-200/60 bg-sky-100/80 p-5 shadow-sm sm:p-7">
            <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase text-sky-700">Compra registrada</p>
                    <h1 class="mt-1 text-3xl font-extrabold text-slate-800">
                        {{ $pagoAprobado ? '¡Pago aprobado!' : 'Pago pendiente' }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-600">
                        {{ $pagoAprobado ? 'Tu pasaje quedó confirmado.' : 'Tu reserva quedó registrada y espera la transferencia.' }}
                    </p>
                </div>

                <span class="rounded-full px-4 py-2 text-xs font-extrabold uppercase shadow-sm {{ $pagoAprobado ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $pago->estado_etiqueta }}
                </span>
            </div>

            <div class="mt-6 rounded-2xl bg-white p-4 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">Viaje</p>
                        <p class="mt-1 text-lg font-extrabold text-slate-800">{{ $pago->origen }} → {{ $pago->destino }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $pago->nombre_pasajero }} · DNI {{ $pago->dni_pasajero }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase text-slate-400">Total</p>
                        <p class="text-2xl font-black text-sky-700">$ {{ number_format($pago->monto, 0, ',', '.') }}</p>
                    </div>
                </div>

                <dl class="grid grid-cols-1 gap-3 pt-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-[10px] font-bold uppercase text-slate-400">Referencia</dt>
                        <dd class="mt-0.5 font-mono text-sm font-bold text-slate-700">{{ $pago->referencia }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase text-slate-400">Método de pago</dt>
                        <dd class="mt-0.5 text-sm font-bold text-slate-700">{{ $pago->metodo_pago_etiqueta }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase text-slate-400">Fecha de compra</dt>
                        <dd class="mt-0.5 text-sm font-bold text-slate-700">{{ $pago->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase text-slate-400">Código de viaje</dt>
                        <dd class="mt-0.5 text-sm font-bold text-slate-700">#{{ $pago->viaje_codigo }}</dd>
                    </div>
                </dl>
            </div>

            @unless($pagoAprobado)
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                    Para completar el pago por transferencia, comunicate con atención al cliente y mencioná la referencia <strong>{{ $pago->referencia }}</strong>. La reserva se mantiene pendiente hasta acreditar el importe.
                </div>
            @endunless

            <div class="mt-5 rounded-xl border border-slate-200 bg-white/70 p-3.5 text-[11px] leading-5 text-slate-500">
                Esta compra forma parte de una demostración: no se realizó ningún cobro real.
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('home') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">Volver al inicio</a>
                <a href="{{ route('pagos.index') }}" class="rounded-xl bg-[#0284c7] px-5 py-3 text-center text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0369a1]">Ver mis compras</a>
            </div>
        </div>
    </div>
@endsection
