@extends('layouts.app')

@section('title', 'Editar ruta')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-bold text-slate-800 dark:text-slate-100">Editar ruta</h1>

    <form action="{{ route('rutas.update', $ruta) }}" method="POST" class="rounded bg-white p-8 shadow dark:bg-slate-800">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="id_ciudad_partida" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Ciudad de partida *</label>
            <select name="id_ciudad_partida" id="id_ciudad_partida" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" @selected(old('id_ciudad_partida', $ruta->id_ciudad_partida) == $ciudad->id_ciudad)>{{ $ciudad->nombre_ciudad }}</option>
                @endforeach
            </select>
            @error('id_ciudad_partida')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="id_ciudad_llegada" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Ciudad de llegada *</label>
            <select name="id_ciudad_llegada" id="id_ciudad_llegada" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" @selected(old('id_ciudad_llegada', $ruta->id_ciudad_llegada) == $ciudad->id_ciudad)>{{ $ciudad->nombre_ciudad }}</option>
                @endforeach
            </select>
            @error('id_ciudad_llegada')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="distancia" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Distancia (km) *</label>
            <input type="number" name="distancia" id="distancia" min="1" value="{{ old('distancia', $ruta->distancia) }}" required
                   class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
            @error('distancia')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="rounded bg-sky-600 px-4 py-2 font-bold text-white hover:bg-sky-700">Guardar cambios</button>
            <a href="{{ route('rutas.index') }}" class="text-sky-600 hover:underline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
