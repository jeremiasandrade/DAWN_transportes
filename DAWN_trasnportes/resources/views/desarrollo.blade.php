
@extends('layouts.app')

@section('title', 'Catálogo de rutas')

@section('content')
<div class="w-full max-w-7xl px-4 py-8">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-2 text-sm font-semibold uppercase text-sky-700">Administración</p>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Catálogo de rutas</h1>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-300">Rutas registradas y módulos pendientes</p>
    </div>

    <section class="mb-10" aria-labelledby="pages-heading">
        <h2 id="pages-heading" class="mb-4 text-xl font-bold text-slate-800 dark:text-slate-100">Páginas generales</h2>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($pages as $page)
                <div class="flex items-center justify-between gap-4 border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                    <div>
                        <h3 class="font-semibold text-slate-800 dark:text-slate-100">{{ $page['name'] }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Vista: {{ $page['viewExists'] ? 'disponible' : 'pendiente' }}
                        </p>
                    </div>
                    @if($page['routeExists'] && $page['viewExists'] && $page['route'] !== 'desarrollo')
                        <a href="{{ route($page['route']) }}" class="shrink-0 font-semibold text-sky-700 hover:underline dark:text-sky-300">Abrir</a>
                    @else
                        <span class="shrink-0 text-sm text-slate-500">{{ $page['routeExists'] ? 'Actual' : 'Pendiente' }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-10" aria-labelledby="registered-routes-heading">
        <div class="mb-4 flex flex-wrap items-baseline justify-between gap-3">
            <h2 id="registered-routes-heading" class="text-xl font-bold text-slate-800 dark:text-slate-100">Rutas registradas</h2>
            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $registeredRoutes->count() }} rutas detectadas en Laravel</p>
        </div>
        <div class="overflow-x-auto border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Método</th>
                        <th class="px-4 py-3 font-semibold">URI</th>
                        <th class="px-4 py-3 font-semibold">Nombre</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($registeredRoutes as $route)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-300">{{ $route['methods'] }}</td>
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-800 dark:text-slate-100">{{ $route['uri'] }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">{{ $route['name'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section aria-labelledby="modules-heading">
        <div class="mb-4 flex flex-wrap items-baseline justify-between gap-3">
            <h2 id="modules-heading" class="text-xl font-bold text-slate-800 dark:text-slate-100">Módulos y acciones</h2>
            <p class="text-sm text-slate-600 dark:text-slate-300">Las acciones con parámetros se abren desde el listado del módulo.</p>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            @foreach($modules as $module)
                <article class="border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $module['name'] }}</h3>
                        @php($indexAction = $module['actions'][0])
                        @if($indexAction['routeExists'] && $indexAction['viewExists'])
                            <a href="{{ route($indexAction['routeName']) }}" class="font-semibold text-sky-700 hover:underline dark:text-sky-300">Abrir listado</a>
                        @else
                            <span class="text-sm font-semibold text-amber-700 dark:text-amber-300">Módulo pendiente</span>
                        @endif
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($module['actions'] as $action)
                            <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3 px-5 py-3 text-sm">
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ $action['label'] }}</p>
                                    <p class="mt-1 break-all font-mono text-xs text-slate-500 dark:text-slate-400">{{ $action['method'] }} {{ $action['uri'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold {{ $action['routeExists'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-amber-700 dark:text-amber-300' }}">
                                        {{ $action['routeExists'] ? 'Ruta activa' : 'Sin ruta' }}
                                    </p>
                                    @if($action['viewName'])
                                        <p class="mt-1 text-xs {{ $action['viewExists'] ? 'text-slate-500 dark:text-slate-400' : 'text-amber-700 dark:text-amber-300' }}">
                                            {{ $action['viewExists'] ? 'Vista disponible' : 'Vista pendiente' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection