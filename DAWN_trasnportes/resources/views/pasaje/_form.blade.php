@php($pasajeActual = $pasaje ?? null)

<div class="mb-4">
    <label for="viaje_id" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Viaje *</label>
    <select name="viaje_id" id="viaje_id" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
        <option value="">Seleccione un viaje</option>
        @foreach($viajes as $viaje)
            <option value="{{ $viaje->id_viaje }}" @selected(old('viaje_id', $pasajeActual->viaje_id ?? null) == $viaje->id_viaje)>
                #{{ $viaje->id_viaje }} — {{ $viaje->ciudadPartida->nombre_ciudad ?? '?' }} → {{ $viaje->ciudadLlegada->nombre_ciudad ?? '?' }}
            </option>
        @endforeach
    </select>
    @error('viaje_id')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
</div>

<div class="mb-4">
    <label for="persona_dni" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Pasajero *</label>
    <select name="persona_dni" id="persona_dni" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
        <option value="">Seleccione una persona</option>
        @foreach($personas as $persona)
            <option value="{{ $persona->dni }}" @selected(old('persona_dni', $pasajeActual->persona_dni ?? null) == $persona->dni)>
                {{ $persona->dni }} — {{ $persona->nombre_completo }}
            </option>
        @endforeach
    </select>
    @error('persona_dni')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
</div>

<div class="mb-4 grid gap-4 sm:grid-cols-2">
    <div>
        <label for="numero_asiento" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Número de asiento *</label>
        <input type="number" name="numero_asiento" id="numero_asiento" min="1" value="{{ old('numero_asiento', $pasajeActual->numero_asiento ?? '') }}" required
               class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
        @error('numero_asiento')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="precio" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Precio *</label>
        <input type="number" name="precio" id="precio" min="0" step="0.01" value="{{ old('precio', $pasajeActual->precio ?? '') }}" required
               class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
        @error('precio')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mb-6">
    <label for="estado" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Estado *</label>
    <select name="estado" id="estado" required class="w-full rounded border px-3 py-2 dark:bg-slate-700 dark:text-slate-100">
        @foreach($estados as $estado)
            <option value="{{ $estado }}" @selected(old('estado', $pasajeActual->estado ?? 'reservado') === $estado)>{{ ucfirst($estado) }}</option>
        @endforeach
    </select>
    @error('estado')<p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>@enderror
</div>
