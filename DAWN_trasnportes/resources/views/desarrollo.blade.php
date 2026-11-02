
@extends('layouts.app')

@section('title', 'Panel de administración')

@section('content')
<div class="mx-auto w-[calc(100%-2rem)] max-w-7xl py-8">
    <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-1 text-xs font-bold uppercase text-sky-700">Dawn Transportes / Gestión</p>
            <h1 class="text-3xl font-black text-slate-900 dark:text-slate-100">Panel de administración</h1>
        </div>
        <a href="{{ route('home.public') }}" class="text-sm font-semibold text-sky-700 hover:underline dark:text-sky-300">Ver sitio público</a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <table class="w-full min-w-195 text-left text-sm">
            <thead class="bg-slate-100 text-xs font-bold uppercase text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                <tr>
                    <th scope="col" class="px-5 py-3">Módulo</th>
                    <th scope="col" class="px-5 py-3">Estado</th>
                    <th scope="col" class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($modules as $module)
                    <tr>
                        <th scope="row" class="px-5 py-4 font-semibold text-slate-800 dark:text-slate-100">{{ $module['name'] }}</th>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-2 text-xs font-semibold {{ $module['indexAvailable'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-amber-700 dark:text-amber-300' }}">
                                <span class="h-2 w-2 rounded-full {{ $module['indexAvailable'] ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                {{ $module['indexAvailable'] ? 'Disponible' : 'En preparación' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-2">
                                @if($module['createAvailable'])
                                    <a href="{{ route($module['createRoute']) }}" class="inline-flex items-center gap-1.5 rounded border border-sky-200 px-3 py-2 text-xs font-bold text-sky-800 hover:bg-sky-50 dark:border-sky-800 dark:text-sky-200 dark:hover:bg-slate-700" aria-label="Crear en {{ $module['name'] }}" title="Crear">
                                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                        Crear
                                    </a>
                                @else
                                    <span class="cursor-not-allowed rounded border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-400 dark:border-slate-700" aria-disabled="true">Crear</span>
                                @endif

                                @if($module['indexAvailable'])
                                    <a href="{{ route($module['indexRoute']) }}" class="inline-flex items-center gap-1.5 rounded border border-slate-300 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700" aria-label="Listar {{ $module['name'] }}" title="Listar">
                                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                        Listar
                                    </a>
                                @else
                                    <span class="cursor-not-allowed rounded border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-400 dark:border-slate-700" aria-disabled="true">Listar</span>
                                @endif

                                @if($module['deleteAvailable'])
                                    <a href="{{ route($module['indexRoute']) }}" class="inline-flex items-center gap-1.5 rounded border border-rose-200 px-3 py-2 text-xs font-bold text-rose-700 hover:bg-rose-50 dark:border-rose-900 dark:text-rose-300 dark:hover:bg-slate-700" aria-label="Eliminar registros de {{ $module['name'] }} desde el listado" title="Eliminar desde el listado">
                                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6"/></svg>
                                        Eliminar
                                    </a>
                                @else
                                    <span class="cursor-not-allowed rounded border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-400 dark:border-slate-700" aria-disabled="true">Eliminar</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection