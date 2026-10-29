@extends('layouts.app')

@section('title', 'Crear destino')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-bold text-slate-800 dark:text-slate-100">Crear destino</h1>

    <form action="{{ route('destinos.store') }}" method="POST" class="rounded bg-white p-8 shadow dark:bg-slate-800">
        @csrf

        <div class="mb-4">
            <label for="nombre_ciudad" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Ciudad *</label>
            <input type="text" name="nombre_ciudad" id="nombre_ciudad" value="{{ old('nombre_ciudad') }}" required
                   class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
            @error('nombre_ciudad')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="provincia" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Provincia *</label>
            <input type="text" name="provincia" id="provincia" value="{{ old('provincia') }}" required
                   class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
            @error('provincia')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="rounded bg-sky-600 px-4 py-2 font-bold text-white hover:bg-sky-700">Crear destino</button>
            <a href="{{ route('destinos.index') }}" class="text-sky-600 hover:underline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
