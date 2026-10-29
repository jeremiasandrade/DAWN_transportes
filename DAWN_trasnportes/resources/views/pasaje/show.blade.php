@extends('layouts.app')

@section('title', 'Detalle del pasaje')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Pasaje #{{ $pasaje->id }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('pasajes.edit', $pasaje) }}" class="rounded bg-yellow-600 px-4 py-2 font-bold text-white hover:bg-yellow-700">Editar</a>
            <a href="{{ route('pasajes.index') }}" class="rounded bg-slate-600 px-4 py-2 font-bold text-white hover:bg-slate-700">Volver</a>
        </div>
    </div>

    <div class="rounded bg-white p-6 shadow dark:bg-slate-800">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Viaje</dt>
                <dd class="text-slate-800 dark:text-slate-100">
                    #{{ $pasaje->viaje_id }} — {{ $pasaje->viaje->ciudadPartida->nombre_ciudad ?? '?' }} → {{ $pasaje->viaje->ciudadLlegada->nombre_ciudad ?? '?' }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Pasajero</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $pasaje->persona->nombre_completo ?? '?' }}</dd>
                <dd class="text-sm text-slate-500 dark:text-slate-400">{{ $pasaje->persona_dni }}</dd>
            </div>
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Asiento</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $pasaje->numero_asiento }}</dd>
            </div>
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Precio</dt>
                <dd class="text-slate-800 dark:text-slate-100">${{ number_format($pasaje->precio, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Estado</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ ucfirst($pasaje->estado) }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
