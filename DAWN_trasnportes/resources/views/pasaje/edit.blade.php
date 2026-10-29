@extends('layouts.app')

@section('title', 'Editar pasaje')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-bold text-slate-800 dark:text-slate-100">Editar pasaje</h1>

    <form action="{{ route('pasajes.update', $pasaje) }}" method="POST" class="rounded bg-white p-8 shadow dark:bg-slate-800">
        @csrf
        @method('PUT')
        @include('pasaje._form')

        <div class="flex items-center justify-between">
            <button type="submit" class="rounded bg-sky-600 px-4 py-2 font-bold text-white hover:bg-sky-700">Guardar cambios</button>
            <a href="{{ route('pasajes.index') }}" class="text-sky-600 hover:underline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
