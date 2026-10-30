@extends('layouts.app')

@section('title', 'Crear ruta')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-bold text-slate-800 dark:text-slate-100">Crear ruta</h1>

    <form action="{{ route('rutas.store') }}" method="POST" class="rounded bg-white p-8 shadow dark:bg-slate-800">
        @csrf

        <div class="mb-4">
            <label for="id_ciudad_partida" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Ciudad de partida *</label>
            <select name="id_ciudad_partida" id="id_ciudad_partida" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
                <option value="">Seleccione una ciudad</option>
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" @selected(old('id_ciudad_partida') == $ciudad->id_ciudad)>{{ $ciudad->etiqueta }}</option>
                @endforeach
            </select>
            @error('id_ciudad_partida')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="id_ciudad_llegada" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Ciudad de llegada *</label>
            <select name="id_ciudad_llegada" id="id_ciudad_llegada" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
                <option value="">Seleccione una ciudad</option>
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad->id_ciudad }}" @selected(old('id_ciudad_llegada') == $ciudad->id_ciudad)>{{ $ciudad->etiqueta }}</option>
                @endforeach
            </select>
            @error('id_ciudad_llegada')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="distancia" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Distancia (km)</label>
            <input type="number" name="distancia" id="distancia" min="1" value="{{ old('distancia') }}"
                   class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
            <p id="distancia-estado" class="mt-1 text-xs text-slate-500 dark:text-slate-400">Se calcula automáticamente con la API de rutas (OSRM) al elegir origen y destino.</p>
            @error('distancia')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="rounded bg-sky-600 px-4 py-2 font-bold text-white hover:bg-sky-700">Crear ruta</button>
            <a href="{{ route('rutas.index') }}" class="text-sky-600 hover:underline">Cancelar</a>
        </div>
    </form>
</div>

        <script>
            (() => {
                const origen = document.getElementById('id_ciudad_partida');
                const destino = document.getElementById('id_ciudad_llegada');
                const distancia = document.getElementById('distancia');
                const estado = document.getElementById('distancia-estado');

                if (!origen || !destino || !distancia || !estado) {
                    return;
                }

                const actualizarDistancia = async () => {
                    estado.textContent = 'Calculando distancia real...';
                    estado.classList.remove('text-red-600', 'text-green-700');

                    try {
                        const url = new URL(@json(route('rutas.distancia')), window.location.origin);
                        url.searchParams.set('id_ciudad_partida', origen.value);
                        url.searchParams.set('id_ciudad_llegada', destino.value);

                        const response = await fetch(url, {
                            headers: { Accept: 'application/json' },
                        });
                        const data = await response.json();

                        if (response.ok && data.distancia) {
                            distancia.value = data.distancia;
                            estado.textContent = 'Distancia calculada automáticamente.';
                            estado.classList.add('text-green-700');
                        } else {
                            estado.textContent = 'No se pudo calcular. Ingresá la distancia manualmente.';
                            estado.classList.add('text-red-600');
                        }
                    } catch (error) {
                        estado.textContent = 'No se pudo calcular. Ingresá la distancia manualmente.';
                        estado.classList.add('text-red-600');
                    }
                };

                origen.addEventListener('change', actualizarDistancia);
                destino.addEventListener('change', actualizarDistancia);
            })();
        </script>
@endsection
