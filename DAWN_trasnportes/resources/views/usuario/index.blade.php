@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="mx-auto w-[calc(100%-2rem)] max-w-7xl py-8">
	<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
		<div>
			<p class="mb-1 text-xs font-bold uppercase text-sky-700">Administración</p>
			<h1 class="text-3xl font-black text-slate-900">Usuarios</h1>
			<p class="mt-1 text-sm text-slate-600">Cuentas registradas en Dawn Transportes.</p>
		</div>
		<a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-800">
			<svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
			Crear usuario
		</a>
	</div>

	@if(session('success'))
		<p role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
	@endif
	@if(session('error'))
		<p role="alert" class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</p>
	@endif

	<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
		<table class="w-full min-w-195 text-left text-sm">
			<thead class="bg-slate-100 text-xs font-bold uppercase text-slate-600">
				<tr>
					<th scope="col" class="px-4 py-3">Usuario</th>
					<th scope="col" class="px-4 py-3">DNI</th>
					<th scope="col" class="px-4 py-3">Correo</th>
					<th scope="col" class="px-4 py-3">Tipo</th>
					<th scope="col" class="px-4 py-3 text-right">Acciones</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-slate-100">
				@forelse($usuarios as $usuario)
					<tr>
						<td class="px-4 py-3 font-semibold text-slate-800">{{ $usuario->name }}</td>
						<td class="px-4 py-3 text-slate-600">{{ $usuario->dni ?: '—' }}</td>
						<td class="px-4 py-3 text-slate-600">{{ $usuario->email }}</td>
						<td class="px-4 py-3">
							<span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $usuario->is_admin ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
								{{ $usuario->is_admin ? 'Administrador' : 'Cliente' }}
							</span>
						</td>
						<td class="px-4 py-3">
							<div class="flex justify-end gap-3">
								<a href="{{ route('usuarios.show', $usuario) }}" class="font-semibold text-sky-700 hover:underline">Ver</a>
								<a href="{{ route('usuarios.edit', $usuario) }}" class="font-semibold text-slate-700 hover:underline">Editar</a>
								<form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cuenta?')">
									@csrf
									@method('DELETE')
									<button type="submit" class="font-semibold text-rose-700 hover:underline">Eliminar</button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Todavía no hay usuarios registrados.</td></tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<div class="mt-4">{{ $usuarios->links() }}</div>
</div>
@endsection
