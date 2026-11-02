@extends('layouts.app')

@section('title', 'Editar viaje')

@section('content')
<div class="mx-auto w-[calc(100%-2rem)] max-w-3xl py-8">
    <div class="mb-6">
        <p class="mb-1 text-xs font-bold uppercase text-sky-700">Administración / Viajes</p>
        <h1 class="text-3xl font-black text-slate-900">Editar viaje #{{ $viaje->id_viaje }}</h1>
    </div>

    <form action="{{ route('admin.viajes.update', $viaje) }}" method="POST" class="space-y-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        @csrf
        @method('PUT')
        @include('admin.viajes._form')
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('admin.viajes.show', $viaje) }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-800">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
