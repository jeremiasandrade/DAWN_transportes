@extends('layouts.app')

@section('title', 'Detalle del destino')

@section('content')
<div class="w-full max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $destino->nombre_ciudad }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('destinos.edit', $destino) }}" class="rounded bg-yellow-600 px-4 py-2 font-bold text-white hover:bg-yellow-700">Editar</a>
            <a href="{{ route('destinos.index') }}" class="rounded bg-slate-600 px-4 py-2 font-bold text-white hover:bg-slate-700">Volver</a>
        </div>
    </div>

    <div class="mb-6 rounded bg-white p-6 shadow dark:bg-slate-800">
        <p class="text-slate-700 dark:text-slate-200"><strong>Provincia:</strong> {{ $destino->provincia->nombre_provincia ?? '-' }}</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded bg-white p-6 shadow dark:bg-slate-800">
            <h2 class="mb-3 text-xl font-bold text-slate-800 dark:text-slate-100">Rutas que salen de aquí</h2>
            @forelse($destino->rutasOrigen as $ruta)
                <p class="text-slate-600 dark:text-slate-300">→ {{ $ruta->destino->nombre_ciudad ?? '-' }} ({{ $ruta->distancia }} km)</p>
            @empty
                <p class="text-slate-500 dark:text-slate-400">Sin rutas de salida.</p>
            @endforelse
        </div>

        <div class="rounded bg-white p-6 shadow dark:bg-slate-800">
            <h2 class="mb-3 text-xl font-bold text-slate-800 dark:text-slate-100">Rutas que llegan aquí</h2>
            @forelse($destino->rutasDestino as $ruta)
                <p class="text-slate-600 dark:text-slate-300">{{ $ruta->origen->nombre_ciudad ?? '-' }} → ({{ $ruta->distancia }} km)</p>
            @empty
                <p class="text-slate-500 dark:text-slate-400">Sin rutas de llegada.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
