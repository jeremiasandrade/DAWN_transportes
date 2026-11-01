@extends('layouts.app')

@section('content')
    <div class="mx-auto w-[calc(100%-2rem)] md:w-[85%] md:max-w-6xl py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Panel de viajes recomendados</h1>
                <p class="text-sm text-slate-600">Solo accesible para usuarios administradores</p>
            </div>
            <a href="{{ route('admin.viajes-recomendados.create') }}" class="rounded-xl bg-[#0284c7] px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-[#0369a1]">Agregar viaje recomendado</a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 p-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto rounded-2xl border border-sky-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-50/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Orden</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Imagen</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Ruta/Título</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Precio</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Descuento</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Vehículo</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Activo</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-600 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-50">
                    @forelse($viajesRecomendados as $item)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->orden }}</td>
                            <td class="px-4 py-3">
                                @if($item->imagen)
                                    <img src="{{ asset('storage/'.$item->imagen) }}" alt="Imagen viaje recomendado" class="h-12 w-20 rounded-lg object-cover">
                                @else
                                    <div class="h-12 w-20 rounded-lg bg-slate-200"></div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-bold text-slate-800">{{ $item->titulo ?: ($item->ruta ?: 'Sin título') }}</div>
                                @if($item->ruta && $item->titulo)
                                    <div class="text-xs text-slate-500">{{ $item->ruta }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-slate-700">${{ number_format($item->precio, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->descuento_porcentaje }}%</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->vehiculo ?: '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->activo ? 'Sí' : 'No' }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.viajes-recomendados.edit', $item) }}" class="text-sky-700 font-semibold hover:underline">Editar</a>
                                <form action="{{ route('admin.viajes-recomendados.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este viaje recomendado?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 font-semibold hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">No hay viajes recomendados cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
