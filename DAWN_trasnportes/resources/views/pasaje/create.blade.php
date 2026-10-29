@extends('layouts.app')

@section('title', 'Crear pasaje')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-bold text-slate-800 dark:text-slate-100">Crear pasaje</h1>

    @if($viajes->isEmpty())
        <div class="mb-4 rounded border border-yellow-400 bg-yellow-100 px-4 py-3 text-yellow-800">
            Todavía no hay viajes registrados. Cargá un viaje para poder emitir pasajes.
        </div>
    @endif

    <form action="{{ route('pasajes.store') }}" method="POST" class="rounded bg-white p-8 shadow dark:bg-slate-800">
        @csrf
        @include('pasaje._form')

        <div class="flex items-center justify-between">
            <button type="submit" class="rounded bg-sky-600 px-4 py-2 font-bold text-white hover:bg-sky-700">Crear pasaje</button>
            <a href="{{ route('pasajes.index') }}" class="text-sky-600 hover:underline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
