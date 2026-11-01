@extends('layouts.app')

@section('content')
    <div class="mx-auto w-[calc(100%-2rem)] md:w-[70%] md:max-w-4xl py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-black text-slate-800">Agregar viaje recomendado</h1>
            <a href="{{ route('admin.viajes-recomendados.index') }}" class="text-sm font-semibold text-sky-700 hover:underline">Volver al listado</a>
        </div>

        <form action="{{ route('admin.viajes-recomendados.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-sky-100 bg-white shadow-sm p-5 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-slate-600">Título</label>
                    <input name="titulo" value="{{ old('titulo') }}" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Ruta (texto a mostrar)</label>
                    <input name="ruta" value="{{ old('ruta') }}" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Viaje relacionado (opcional)</label>
                    <select name="viaje_id" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                        <option value="">Sin vincular</option>
                        @foreach($viajes as $viaje)
                            <option value="{{ $viaje->id_viaje }}" @selected(old('viaje_id') == $viaje->id_viaje)>
                                {{ $viaje->ciudadPartida?->nombre_ciudad }} → {{ $viaje->ciudadLlegada?->nombre_ciudad }} ({{ $viaje->fecha_partida?->format('d/m/Y H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Precio manual *</label>
                    <input name="precio" type="number" step="0.01" min="0" value="{{ old('precio') }}" required class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Descuento (%)</label>
                    <input name="descuento_porcentaje" type="number" min="0" max="100" value="{{ old('descuento_porcentaje', 0) }}" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Vehículo</label>
                    <input name="vehiculo" value="{{ old('vehiculo') }}" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Orden</label>
                    <input name="orden" type="number" min="0" value="{{ old('orden', 0) }}" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div class="flex items-center gap-2 mt-6">
                    <input id="activo" name="activo" type="checkbox" value="1" checked class="rounded border-sky-300">
                    <label for="activo" class="text-sm text-slate-700">Activo</label>
                </div>
                <div class="md:col-span-2">
                    <label for="imagen" class="text-xs font-semibold text-slate-600">Imagen para la tarjeta del inicio</label>
                    <input id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="imagen_portada" class="text-xs font-semibold text-slate-600">Imagen de portada del detalle</label>
                    <input id="imagen_portada" name="imagen_portada" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded-xl border border-sky-100 px-3 py-2.5 text-sm">
                </div>
            </div>

            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-end">
                <button class="rounded-xl bg-[#0284c7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#0369a1]">Guardar</button>
            </div>
        </form>
    </div>
@endsection
