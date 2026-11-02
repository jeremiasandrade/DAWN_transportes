@php($viajeActual = $viaje ?? null)

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="id_ciudad_partida" class="mb-1.5 block text-sm font-bold text-slate-700">Ciudad de partida *</label>
        <select name="id_ciudad_partida" id="id_ciudad_partida" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
            <option value="">Seleccione ciudad</option>
            @foreach($ciudades as $ciudad)
                <option value="{{ $ciudad->id_ciudad }}" @selected((string) old('id_ciudad_partida', $viajeActual?->id_ciudad_partida) === (string) $ciudad->id_ciudad)>{{ $ciudad->etiqueta }}</option>
            @endforeach
        </select>
        @error('id_ciudad_partida')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="id_ciudad_llegada" class="mb-1.5 block text-sm font-bold text-slate-700">Ciudad de llegada *</label>
        <select name="id_ciudad_llegada" id="id_ciudad_llegada" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
            <option value="">Seleccione ciudad</option>
            @foreach($ciudades as $ciudad)
                <option value="{{ $ciudad->id_ciudad }}" @selected((string) old('id_ciudad_llegada', $viajeActual?->id_ciudad_llegada) === (string) $ciudad->id_ciudad)>{{ $ciudad->etiqueta }}</option>
            @endforeach
        </select>
        @error('id_ciudad_llegada')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-slate-500">Solo se aceptan rutas previamente registradas.</p>
    </div>

    <div>
        <label for="cliente_dni" class="mb-1.5 block text-sm font-bold text-slate-700">Cliente responsable *</label>
        <select name="cliente_dni" id="cliente_dni" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
            <option value="">Seleccione cliente</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->persona_dni }}" @selected(old('cliente_dni', $viajeActual?->cliente_dni) === $cliente->persona_dni)>
                    {{ $cliente->persona?->nombre_completo ?? $cliente->persona_dni }} · {{ $cliente->persona_dni }}
                </option>
            @endforeach
        </select>
        @error('cliente_dni')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="vehiculo_id" class="mb-1.5 block text-sm font-bold text-slate-700">Vehículo *</label>
        <select name="vehiculo_id" id="vehiculo_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
            <option value="">Seleccione vehículo</option>
            @foreach($vehiculos as $vehiculo)
                <option value="{{ $vehiculo->id_vehiculo }}" @selected((string) old('vehiculo_id', $viajeActual?->vehiculo_id) === (string) $vehiculo->id_vehiculo)>
                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }} · {{ $vehiculo->patente }}
                </option>
            @endforeach
        </select>
        @error('vehiculo_id')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="fecha_partida" class="mb-1.5 block text-sm font-bold text-slate-700">Fecha y hora de partida *</label>
        <input type="datetime-local" name="fecha_partida" id="fecha_partida" required value="{{ old('fecha_partida', $viajeActual?->fecha_partida?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
        @error('fecha_partida')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="fecha_llegada" class="mb-1.5 block text-sm font-bold text-slate-700">Fecha y hora de llegada *</label>
        <input type="datetime-local" name="fecha_llegada" id="fecha_llegada" required value="{{ old('fecha_llegada', $viajeActual?->fecha_llegada?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
        @error('fecha_llegada')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
    </div>
</div>

@if($errors->any())
    <div role="alert" class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        Revisá los campos marcados antes de guardar.
    </div>
@endif

