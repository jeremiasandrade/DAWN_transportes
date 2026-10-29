@extends('layouts.app')

@section('title', 'Rutas')

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Rutas</h1>
            <p class="text-slate-500 dark:text-slate-400">Tramos entre ciudades con su distancia.</p>
        </div>
        <a href="{{ route('rutas.create') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">Agregar ruta</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto rounded bg-white shadow dark:bg-slate-800">
        <table class="min-w-full">
            <thead class="bg-slate-100 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Partida</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Llegada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Distancia</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($rutas as $ruta)
                    <tr>
                        <td class="px-6 py-4 text-slate-800 dark:text-slate-100">{{ $ruta->origen->nombre_ciudad ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-800 dark:text-slate-100">{{ $ruta->destino->nombre_ciudad ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $ruta->distancia }} km</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('rutas.show', $ruta) }}" class="text-sky-600 hover:underline">Ver</a>
                                <a href="{{ route('rutas.edit', $ruta) }}" class="text-yellow-600 hover:underline">Editar</a>
                                <form action="{{ route('rutas.destroy', $ruta) }}" method="POST" onsubmit="return confirm('¿Eliminar ruta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-slate-500 dark:text-slate-400">Todavía no hay rutas cargadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
