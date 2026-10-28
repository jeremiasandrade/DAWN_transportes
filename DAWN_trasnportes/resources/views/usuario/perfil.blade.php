@extends('layouts.app')

@section('content')
	@php
		$firstName = $user->first_name ?: explode(' ', $user->name)[0];
		$initials = mb_strtoupper(mb_substr($user->first_name ?: $user->name, 0, 1).mb_substr($user->last_name ?? '', 0, 1));
		$genderLabels = [
			'femenino' => 'Femenino',
			'masculino' => 'Masculino',
			'prefiero-no-decirlo' => 'Prefiero no decirlo',
		];
		$profileFields = [
			['Nombre', $user->first_name],
			['Apellido', $user->last_name],
			['Documento de identidad', $user->dni],
			['Género', $genderLabels[$user->gender] ?? $user->gender],
			['Teléfono', $user->contact],
			['Email', $user->email],
		];
	@endphp

	<div data-profile-page data-invalid-dialog="{{ $errors->any() ? ($errors->hasAny(['first_name', 'last_name', 'dni', 'gender', 'contact', 'email']) ? 'edit' : 'password') : '' }}" class="mx-auto w-[calc(100%-2rem)] max-w-6xl py-5 md:py-8">
		<div class="mb-6 flex items-end justify-between gap-4 md:mb-8">
			<div>
				<p class="mb-1.5 text-[0.68rem] font-extrabold uppercase tracking-[0.18em] text-sky-700">Área personal</p>
				<h1 class="text-3xl font-black text-slate-900">Mi cuenta</h1>
			</div>
			<div class="hidden items-center gap-2 rounded-full bg-white px-3.5 py-2 text-xs font-bold text-slate-600 shadow-sm sm:flex">
				@if ($user->email_verified_at)
					<svg aria-hidden="true" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></svg>
					Cuenta verificada
				@else
					<svg aria-hidden="true" class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
					Email sin verificar
				@endif
			</div>
		</div>

		<div class="grid gap-5 lg:grid-cols-[224px_minmax(0,1fr)] xl:gap-6">
			<aside class="self-start overflow-hidden rounded-2xl bg-sky-100 shadow-[0_14px_40px_rgba(14,116,144,0.08)]">
				<div class="border-b border-sky-200/70 p-3.5">
					<div class="flex min-w-0 items-center gap-3 rounded-xl bg-white/75 p-3">
						<div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-sky-500 bg-white text-sm font-black text-sky-700">{{ $initials }}</div>
						<div class="min-w-0">
							<p class="truncate text-xs font-extrabold text-slate-900">¡Hola, {{ $firstName }}!</p>
							<p class="truncate text-[10px] text-slate-500">{{ $user->email }}</p>
						</div>
					</div>
				</div>

				<nav data-profile-navigation aria-label="Secciones de la cuenta" class="p-2">
					<div class="flex gap-1.5 overflow-x-auto pb-1 lg:block lg:space-y-1 lg:overflow-visible">
						@foreach ([['Mis datos', 'edit'], ['Mis viajes', 'bus'], ['Pasajeros favoritos', 'heart'], ['Mis dispositivos', 'laptop'], ['Mi Pase Dawn', 'card'], ['Centro de ayuda', 'help'], ['Condiciones generales', 'info']] as [$label, $icon])
							<button type="button" data-profile-tab="{{ $label }}" aria-current="{{ $loop->first ? 'page' : 'false' }}" class="inline-flex min-w-max items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-[11px] font-bold transition-colors lg:flex lg:w-full {{ $loop->first ? 'bg-white text-sky-800 shadow-sm' : 'text-slate-600 hover:bg-white/60 hover:text-slate-900' }}">
								@if ($icon === 'edit')
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 4 4 4L8 20H4v-4L16 4Z"/><path d="m14 6 4 4"/></svg>
								@elseif ($icon === 'bus')
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 1 0 4 0m-4 0H3V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10h-2m-10 0h6m4 0a2 2 0 1 1-4 0m4 0h2M7 9h10M7 12h10"/></svg>
								@elseif ($icon === 'heart')
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/></svg>
								@elseif ($icon === 'laptop')
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="12" rx="2"/><path d="M2 20h20"/></svg>
								@elseif ($icon === 'card')
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18m-14 5h4"/></svg>
								@elseif ($icon === 'help')
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3m.1 4h.01"/></svg>
								@else
									<svg aria-hidden="true" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>
								@endif
								<span class="flex-1">{{ $label }}</span>
								<svg aria-hidden="true" class="hidden h-3.5 w-3.5 lg:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
							</button>
						@endforeach
					</div>
				</nav>

				<form method="POST" action="{{ route('logout') }}" class="p-2 pt-0">
					@csrf
					<button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-[11px] font-extrabold text-sky-800 transition-colors hover:bg-white/60">
						<svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5m5 5H3"/><path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></svg>
						Cerrar sesión
					</button>
				</form>
			</aside>

			<section class="min-w-0 rounded-2xl bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.06)] md:p-7">
				<div data-profile-content="Mis datos">
					<div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
						<div>
							<p class="text-[0.68rem] font-extrabold uppercase tracking-[0.16em] text-sky-700">Perfil del pasajero</p>
							<h2 class="mt-1.5 text-2xl font-black text-slate-900">Mis datos</h2>
							<p class="mt-1.5 text-xs leading-5 text-slate-500">Estos datos se usarán para agilizar tus próximas reservas.</p>
						</div>
						<button type="button" data-open-profile-editor class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-3.5 py-2 text-[11px] font-extrabold text-white shadow-sm transition-colors hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-200">
							<svg aria-hidden="true" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 4 4 4L8 20H4v-4L16 4Z"/><path d="m14 6 4 4"/></svg>
							Editar información
						</button>
					</div>

					@if (session('status'))
						<div role="status" class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-800">
							{{ session('status') === 'profile-updated' ? 'Tus datos se actualizaron correctamente.' : 'Tu contraseña se actualizó correctamente.' }}
						</div>
					@endif

					<dl class="grid gap-x-7 pt-2 sm:grid-cols-2">
						@foreach ($profileFields as [$label, $value])
							<div class="min-w-0 border-b border-slate-100 py-3.5">
								<dt class="text-[0.62rem] font-extrabold uppercase tracking-[0.12em] text-slate-400">{{ $label }}</dt>
								<dd class="mt-1 break-words text-xs font-bold text-slate-800">{{ filled($value) ? $value : 'No informado' }}</dd>
							</div>
						@endforeach
					</dl>

					<div class="mt-5 flex flex-col gap-3 rounded-xl bg-sky-50 p-4 sm:flex-row sm:items-center sm:justify-between">
						<div>
							<p class="text-[0.62rem] font-extrabold uppercase tracking-[0.12em] text-slate-400">Contraseña</p>
							<p class="mt-1 font-black tracking-[0.28em] text-slate-800" aria-label="Contraseña oculta">••••••••••</p>
						</div>
						<button type="button" data-open-password-editor class="self-start text-xs font-extrabold text-sky-800 underline decoration-sky-300 underline-offset-4 hover:text-sky-950">Cambiar contraseña</button>
					</div>
				</div>

				@foreach (['Mis viajes', 'Pasajeros favoritos', 'Mis dispositivos', 'Mi Pase Dawn', 'Centro de ayuda', 'Condiciones generales'] as $sectionTitle)
					<div data-profile-content="{{ $sectionTitle }}" class="hidden min-h-[360px] flex-col items-center justify-center text-center">
						<div class="mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-sky-100 text-sky-700">
							<svg aria-hidden="true" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg>
						</div>
						<p class="text-[0.68rem] font-extrabold uppercase tracking-[0.16em] text-sky-700">Área personal</p>
						<h2 class="mt-1.5 text-2xl font-black text-slate-900">{{ $sectionTitle }}</h2>
						<p class="mt-2 max-w-sm text-sm leading-6 text-slate-500">Esta sección estará disponible próximamente.</p>
						<button type="button" data-back-to-profile class="mt-5 rounded-lg bg-sky-600 px-4 py-2.5 text-xs font-extrabold text-white transition-colors hover:bg-sky-700">Volver a mis datos</button>
					</div>
				@endforeach
			</section>
		</div>
	</div>

	<div data-profile-dialog="edit" class="fixed inset-0 z-50 hidden place-items-center overflow-y-auto bg-slate-950/35 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="profile-edit-title">
		<form method="POST" action="{{ route('profile.update') }}" class="my-auto max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-5 shadow-2xl md:p-7">
			@csrf
			@method('PUT')
			<div class="mb-5 flex items-start justify-between gap-4">
				<div>
					<p class="text-[0.68rem] font-extrabold uppercase tracking-[0.16em] text-sky-700">Perfil</p>
					<h2 id="profile-edit-title" class="mt-1 text-xl font-black text-slate-900">Editar información</h2>
				</div>
				<button type="button" data-close-profile-dialog class="grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200" aria-label="Cerrar edición">
					<svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 6-12 12M6 6l12 12"/></svg>
				</button>
			</div>
			<div class="grid gap-3.5 sm:grid-cols-2">
				@foreach ([['first_name', 'Nombre', 'text'], ['last_name', 'Apellido', 'text'], ['dni', 'Documento de identidad', 'text'], ['contact', 'Teléfono', 'tel'], ['email', 'Email', 'email']] as [$field, $label, $type])
					<label class="flex min-w-0 flex-col gap-1.5 text-xs font-bold text-slate-600">
						{{ $label }}
						<input name="{{ $field }}" type="{{ $type }}" value="{{ old($field, $user->{$field}) }}" required class="w-full rounded-lg border border-sky-100 bg-sky-50/70 px-3.5 py-2.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
						@error($field)<span class="text-xs font-medium text-red-600">{{ $message }}</span>@enderror
					</label>
				@endforeach
				<label class="flex min-w-0 flex-col gap-1.5 text-xs font-bold text-slate-600">
					Género
					<select name="gender" required class="w-full rounded-lg border border-sky-100 bg-sky-50/70 px-3.5 py-2.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
						<option value="femenino" @selected(old('gender', $user->gender) === 'femenino')>Femenino</option>
						<option value="masculino" @selected(old('gender', $user->gender) === 'masculino')>Masculino</option>
						<option value="prefiero-no-decirlo" @selected(old('gender', $user->gender) === 'prefiero-no-decirlo')>Prefiero no decirlo</option>
					</select>
					@error('gender')<span class="text-xs font-medium text-red-600">{{ $message }}</span>@enderror
				</label>
			</div>
			<div class="mt-6 flex flex-col-reverse gap-2.5 sm:flex-row sm:justify-end">
				<button type="button" data-close-profile-dialog class="rounded-lg px-4 py-2.5 text-xs font-extrabold text-slate-600 transition-colors hover:bg-slate-100">Cancelar</button>
				<button type="submit" class="rounded-lg bg-sky-600 px-4 py-2.5 text-xs font-extrabold text-white transition-colors hover:bg-sky-700">Guardar cambios</button>
			</div>
		</form>
	</div>

	<div data-profile-dialog="password" class="fixed inset-0 z-50 hidden place-items-center overflow-y-auto bg-slate-950/35 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="password-edit-title">
		<form method="POST" action="{{ route('profile.password.update') }}" class="my-auto w-full max-w-md rounded-2xl bg-white p-5 shadow-2xl md:p-7">
			@csrf
			@method('PUT')
			<div class="mb-5 flex items-start justify-between gap-4">
				<div>
					<p class="text-[0.68rem] font-extrabold uppercase tracking-[0.16em] text-sky-700">Seguridad</p>
					<h2 id="password-edit-title" class="mt-1 text-xl font-black text-slate-900">Cambiar contraseña</h2>
				</div>
				<button type="button" data-close-profile-dialog class="grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200" aria-label="Cerrar cambio de contraseña">
					<svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 6-12 12M6 6l12 12"/></svg>
				</button>
			</div>
			<div class="grid gap-3.5">
				@foreach ([['current_password', 'Contraseña actual'], ['password', 'Nueva contraseña'], ['password_confirmation', 'Confirmar nueva contraseña']] as [$field, $label])
					<label class="flex flex-col gap-1.5 text-xs font-bold text-slate-600">
						{{ $label }}
						<input name="{{ $field }}" type="password" required autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}" class="w-full rounded-lg border border-sky-100 bg-sky-50/70 px-3.5 py-2.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
						@error($field)<span class="text-xs font-medium text-red-600">{{ $message }}</span>@enderror
					</label>
				@endforeach
			</div>
			<div class="mt-6 flex flex-col-reverse gap-2.5 sm:flex-row sm:justify-end">
				<button type="button" data-close-profile-dialog class="rounded-lg px-4 py-2.5 text-xs font-extrabold text-slate-600 transition-colors hover:bg-slate-100">Cancelar</button>
				<button type="submit" class="rounded-lg bg-sky-600 px-4 py-2.5 text-xs font-extrabold text-white transition-colors hover:bg-sky-700">Actualizar contraseña</button>
			</div>
		</form>
	</div>

	<script>
		(() => {
			const tabs = Array.from(document.querySelectorAll('[data-profile-tab]'));
			const sections = Array.from(document.querySelectorAll('[data-profile-content]'));
			const dialogs = Array.from(document.querySelectorAll('[data-profile-dialog]'));

			const selectSection = (sectionName) => {
				tabs.forEach((tab) => {
					const isActive = tab.dataset.profileTab === sectionName;
					tab.setAttribute('aria-current', isActive ? 'page' : 'false');
					tab.classList.toggle('bg-white', isActive);
					tab.classList.toggle('text-sky-800', isActive);
					tab.classList.toggle('shadow-sm', isActive);
					tab.classList.toggle('text-slate-600', !isActive);
				});

				sections.forEach((section) => {
					const isActive = section.dataset.profileContent === sectionName;
					section.classList.toggle('hidden', !isActive);
					section.classList.toggle('flex', isActive && section.dataset.profileContent !== 'Mis datos');
				});
			};

			const closeDialogs = () => dialogs.forEach((dialog) => {
				dialog.classList.add('hidden');
				dialog.classList.remove('grid');
			});

			tabs.forEach((tab) => tab.addEventListener('click', () => selectSection(tab.dataset.profileTab)));
			document.querySelectorAll('[data-back-to-profile]').forEach((button) => button.addEventListener('click', () => selectSection('Mis datos')));
			document.querySelector('[data-open-profile-editor]').addEventListener('click', () => {
				const dialog = document.querySelector('[data-profile-dialog="edit"]');
				dialog.classList.remove('hidden');
				dialog.classList.add('grid');
			});
			document.querySelector('[data-open-password-editor]').addEventListener('click', () => {
				const dialog = document.querySelector('[data-profile-dialog="password"]');
				dialog.classList.remove('hidden');
				dialog.classList.add('grid');
			});
			document.querySelectorAll('[data-close-profile-dialog]').forEach((button) => button.addEventListener('click', closeDialogs));
			dialogs.forEach((dialog) => dialog.addEventListener('click', (event) => {
				if (event.target === dialog) {
					closeDialogs();
				}
			}));
			document.addEventListener('keydown', (event) => {
				if (event.key === 'Escape') {
					closeDialogs();
				}
			});

			const invalidDialog = document.querySelector('[data-profile-page]').dataset.invalidDialog;
			if (invalidDialog) {
				const dialog = document.querySelector(`[data-profile-dialog="${invalidDialog}"]`);
				dialog.classList.remove('hidden');
				dialog.classList.add('grid');
			}
		})();
	</script>
@endsection