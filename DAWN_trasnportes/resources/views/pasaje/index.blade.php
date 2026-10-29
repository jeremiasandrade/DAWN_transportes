@extends('layouts.app')

@section('title', 'Pasajes')

@section('content')
<div class="w-full max-w-6xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Pasajes</h1>
            <p class="text-slate-500 dark:text-slate-400">Pasajes vendidos o reservados por viaje.</p>
        </div>
        <a href="{{ route('pasajes.create') }}" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">Agregar pasaje</a>
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
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Viaje</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Pasajero</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Asiento</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Precio</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase text-slate-500 dark:text-slate-300">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($pasajes as $pasaje)
                    <tr>
                        <td class="px-4 py-4 text-slate-800 dark:text-slate-100">
                            #{{ $pasaje->viaje_id }}
                            <span class="block text-sm text-slate-500 dark:text-slate-400">
                                {{ $pasaje->viaje->ciudadPartida->nombre_ciudad ?? '?' }} → {{ $pasaje->viaje->ciudadLlegada->nombre_ciudad ?? '?' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-slate-800 dark:text-slate-100">
                            {{ $pasaje->persona->nombre_completo ?? '?' }}
                            <span class="block text-sm text-slate-500 dark:text-slate-400">{{ $pasaje->persona_dni }}</span>
                        </td>
                        <td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $pasaje->numero_asiento }}</td>
                        <td class="px-4 py-4 text-slate-600 dark:text-slate-300">${{ number_format($pasaje->precio, 2) }}</td>
                        <td class="px-4 py-4">
                            <span class="rounded bg-slate-200 px-2 py-1 text-xs font-bold uppercase text-slate-700 dark:bg-slate-700 dark:text-slate-200">{{ $pasaje->estado }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('pasajes.show', $pasaje) }}" class="text-sky-600 hover:underline">Ver</a>
                                <a href="{{ route('pasajes.edit', $pasaje) }}" class="text-yellow-600 hover:underline">Editar</a>
                                <form action="{{ route('pasajes.destroy', $pasaje) }}" method="POST" onsubmit="return confirm('¿Eliminar pasaje?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-slate-500 dark:text-slate-400">Todavía no hay pasajes cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
