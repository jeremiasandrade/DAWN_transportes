@extends('layouts.app')

@section('title', 'Detalle de la ruta')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Detalle de la ruta</h1>
        <div class="flex gap-2">
            <a href="{{ route('rutas.edit', $ruta) }}" class="rounded bg-yellow-600 px-4 py-2 font-bold text-white hover:bg-yellow-700">Editar</a>
            <a href="{{ route('rutas.index') }}" class="rounded bg-slate-600 px-4 py-2 font-bold text-white hover:bg-slate-700">Volver</a>
        </div>
    </div>

    <div class="rounded bg-white p-6 shadow dark:bg-slate-800">
        <dl class="grid gap-4 sm:grid-cols-3">
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Partida</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $ruta->origen->nombre_ciudad ?? '-' }}</dd>
                <dd class="text-sm text-slate-500 dark:text-slate-400">{{ $ruta->origen->provincia->nombre_provincia ?? '' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Llegada</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $ruta->destino->nombre_ciudad ?? '-' }}</dd>
                <dd class="text-sm text-slate-500 dark:text-slate-400">{{ $ruta->destino->provincia->nombre_provincia ?? '' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Distancia</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $ruta->distancia }} km</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
