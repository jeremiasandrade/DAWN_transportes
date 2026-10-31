@extends('layouts.app')

@section('title', 'Mis compras')

@section('content')
    <div class="mx-auto w-[calc(100%-2rem)] max-w-5xl pb-8">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase text-sky-700">Mi cuenta</p>
                <h1 class="mt-1 text-3xl font-extrabold text-slate-800">Mis compras</h1>
                <p class="mt-1 text-sm text-slate-500">Comprobantes de pasajes comprados desde tu cuenta.</p>
            </div>
            <a href="{{ route('home') }}" class="rounded-xl bg-[#0284c7] px-4 py-2.5 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0369a1]">Buscar viajes</a>
        </div>

        @if($pagos->isEmpty())
            <div class="rounded-[22px] border border-sky-200/60 bg-sky-100/80 p-8 text-center shadow-sm">
                <svg aria-hidden="true" class="mx-auto h-10 w-10 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 8v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-8Z"/><path d="M13 5v2m0 4v2m0 4v2"/></svg>
                <h2 class="mt-3 text-lg font-bold text-slate-800">Todavía no tenés compras</h2>
                <p class="mt-1 text-sm text-slate-600">Cuando compres un pasaje, el comprobante va a aparecer acá.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-[22px] border border-sky-200/60 bg-white shadow-sm">
                <table class="min-w-full">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase text-slate-500">Referencia</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase text-slate-500">Viaje</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase text-slate-500">Pasajero</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase text-slate-500">Total</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold uppercase text-slate-500">Estado</th>
                            <th class="px-4 py-3 text-right text-[10px] font-bold uppercase text-slate-500">Comprobante</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pagos as $pago)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-mono text-xs font-bold text-slate-700">{{ $pago->referencia }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm font-bold text-slate-800">{{ $pago->origen }} → {{ $pago->destino }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">{{ $pago->metodo_pago_etiqueta }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm text-slate-700">{{ $pago->nombre_pasajero }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">DNI {{ $pago->dni_pasajero }}</p>
                                </td>
                                <td class="px-4 py-4 text-sm font-extrabold text-sky-700">$ {{ number_format($pago->monto, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $pago->estado === 'aprobado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $pago->estado_etiqueta }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('pagos.show', $pago) }}" class="text-sm font-bold text-sky-700 hover:underline">Ver detalle</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
