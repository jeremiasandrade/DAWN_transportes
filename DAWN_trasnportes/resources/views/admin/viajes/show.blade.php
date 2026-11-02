@extends('layouts.app')

@section('title', 'Detalle del viaje')

@section('content')
<div class="mx-auto w-[calc(100%-2rem)] max-w-4xl py-8">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-1 text-xs font-bold uppercase text-sky-700">Administración / Viajes</p>
            <h1 class="text-3xl font-black text-slate-900">{{ $viaje->ciudadPartida?->nombre_ciudad }} → {{ $viaje->ciudadLlegada?->nombre_ciudad }}</h1>
            <p class="mt-1 text-sm text-slate-500">Viaje #{{ $viaje->id_viaje }}</p>
        </div>
        <a href="{{ route('admin.viajes.edit', $viaje) }}" class="rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-800">Editar viaje</a>
    </div>

    @if(session('success'))
        <p role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    @endif

    <dl class="grid gap-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 sm:p-7">
        <div><dt class="text-xs font-bold uppercase text-slate-500">Partida</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->ciudadPartida?->etiqueta }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Llegada</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->ciudadLlegada?->etiqueta }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Fecha de partida</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->fecha_partida?->format('d/m/Y H:i') }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Fecha de llegada</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->fecha_llegada?->format('d/m/Y H:i') }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Cliente</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->cliente?->persona?->nombre_completo ?? $viaje->cliente_dni }} · {{ $viaje->cliente_dni }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Vehículo</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->vehiculo?->marca }} {{ $viaje->vehiculo?->modelo }} · {{ $viaje->vehiculo?->patente }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Pasajes asociados</dt><dd class="mt-1 font-semibold text-slate-900">{{ $viaje->pasajes_count }}</dd></div>
    </dl>

    <div class="mt-5 flex flex-wrap justify-between gap-3">
        <a href="{{ route('admin.viajes.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Volver al listado</a>
        @if($viaje->pasajes_count === 0)
            <form action="{{ route('admin.viajes.destroy', $viaje) }}" method="POST" onsubmit="return confirm('¿Eliminar este viaje?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-rose-200 px-4 py-2.5 text-sm font-bold text-rose-700 hover:bg-rose-50">Eliminar viaje</button>
            </form>
        @else
            <p class="self-center text-sm text-slate-500">Este viaje ya tiene pasajes y no puede eliminarse.</p>
        @endif
    </div>
</div>
@endsection
