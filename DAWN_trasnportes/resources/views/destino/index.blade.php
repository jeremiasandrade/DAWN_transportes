@extends('layouts.app')

@section('title', 'Destinos')

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Destinos</h1>
            <p class="text-slate-500 dark:text-slate-400">Ciudades disponibles para crear rutas.</p>
        </div>
        <a href="{{ route('destinos.create') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">Agregar destino</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('destinos.index') }}" class="mb-5 flex flex-wrap items-end gap-3">
        <div class="min-w-60 flex-1">
            <label for="q" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-200">Buscar ciudad o provincia</label>
            <input id="q" name="q" type="search" value="{{ $busqueda }}" placeholder="Ej.: Posadas" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-100 dark:bg-slate-700 dark:text-slate-100">
        </div>
        <div class="min-w-52">
            <label for="orden" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-200">Ordenar por</label>
            <select id="orden" name="orden" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-100 dark:bg-slate-700 dark:text-slate-100">
                <option value="provincia" @selected($orden === 'provincia')>Provincia (A-Z)</option>
                <option value="nombre_asc" @selected($orden === 'nombre_asc')>Ciudad (A-Z)</option>
                <option value="nombre_desc" @selected($orden === 'nombre_desc')>Ciudad (Z-A)</option>
            </select>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-sky-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-800">
            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Buscar
        </button>
        @if($busqueda !== '' || $orden !== 'provincia')
            <a href="{{ route('destinos.index') }}" class="px-2 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Limpiar filtros</a>
        @endif
    </form>

    <div class="overflow-x-auto rounded bg-white shadow dark:bg-slate-800">
        <table class="min-w-full">
            <thead class="bg-slate-100 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Ciudad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Provincia</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($ciudades as $ciudad)
                    <tr>
                        <td class="px-6 py-4 text-slate-800 dark:text-slate-100">{{ $ciudad->nombre_ciudad }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $ciudad->provincia->nombre_provincia ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('destinos.show', $ciudad) }}" class="text-sky-600 hover:underline">Ver</a>
                                <a href="{{ route('destinos.edit', $ciudad) }}" class="text-yellow-600 hover:underline">Editar</a>
                                <form action="{{ route('destinos.destroy', $ciudad) }}" method="POST" onsubmit="return confirm('¿Eliminar destino?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-slate-500 dark:text-slate-400">Todavía no hay destinos cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $ciudades->links() }}</div>
</div>
@endsection
