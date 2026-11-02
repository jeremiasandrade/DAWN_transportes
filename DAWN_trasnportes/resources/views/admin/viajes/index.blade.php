@extends('layouts.app')

@section('title', 'Viajes')

@section('content')
<div class="mx-auto w-[calc(100%-2rem)] max-w-7xl py-8">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-1 text-xs font-bold uppercase text-sky-700">Administración</p>
            <h1 class="text-3xl font-black text-slate-900">Viajes</h1>
            <p class="mt-1 text-sm text-slate-600">Salidas registradas y asignaciones.</p>
        </div>
        <a href="{{ route('admin.viajes.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-800">
            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Crear viaje
        </a>
    </div>

    @if(session('success'))
        <p role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p role="alert" class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</p>
    @endif

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-195 text-left text-sm">
            <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-600">
                <tr>
                    <th scope="col" class="px-4 py-3">Ruta</th>
                    <th scope="col" class="px-4 py-3">Salida</th>
                    <th scope="col" class="px-4 py-3">Llegada</th>
                    <th scope="col" class="px-4 py-3">Cliente</th>
                    <th scope="col" class="px-4 py-3">Vehículo</th>
                    <th scope="col" class="px-4 py-3">Pasajes</th>
                    <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($viajes as $viaje)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ $viaje->ciudadPartida?->nombre_ciudad }} → {{ $viaje->ciudadLlegada?->nombre_ciudad }}
                            <span class="block text-xs font-normal text-slate-500">#{{ $viaje->id_viaje }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $viaje->fecha_partida?->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $viaje->fecha_llegada?->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $viaje->cliente?->persona?->nombre_completo ?? $viaje->cliente_dni }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $viaje->vehiculo?->marca }} {{ $viaje->vehiculo?->modelo }} · {{ $viaje->vehiculo?->patente }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $viaje->pasajes_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.viajes.show', $viaje) }}" class="font-semibold text-sky-700 hover:underline">Ver</a>
                                <a href="{{ route('admin.viajes.edit', $viaje) }}" class="font-semibold text-slate-700 hover:underline">Editar</a>
                                <form action="{{ route('admin.viajes.destroy', $viaje) }}" method="POST" onsubmit="return confirm('¿Eliminar este viaje?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @disabled($viaje->pasajes_count > 0) title="{{ $viaje->pasajes_count > 0 ? 'No se puede eliminar un viaje con pasajes' : 'Eliminar viaje' }}" class="font-semibold text-rose-700 hover:underline disabled:cursor-not-allowed disabled:text-slate-400 disabled:no-underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">Todavía no hay viajes registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $viajes->links() }}</div>
</div>
@endsection
