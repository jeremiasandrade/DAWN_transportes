@extends('layouts.app')

@section('content')
        <div class="mx-4 w-full max-w-89 rounded-[20px] border border-sky-200/50 bg-sky-100/80 p-8 shadow-md">
            <h2 class="mb-6 text-center text-2xl font-bold text-slate-800">
                Bienvenido
            </h2>
            
            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label for="username" class="ml-1 text-xs font-semibold text-slate-700">
                        Usuario
                    </label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username') }}"
                        placeholder="Ingresa tu usuario"
                        class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                        required
                        autofocus
                    />
                    @error('username')
                        <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="password" class="ml-1 text-xs font-semibold text-slate-700">
                        Contraseña
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none ring-2 ring-transparent transition-all focus:border-transparent focus:ring-sky-400"
                        required
                    />
                    @error('password')
                        <span class="text-xs text-red-500 ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-2 flex flex-col gap-3">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-[#0284c7] py-3 text-center text-sm font-semibold text-white shadow-md transition-all hover:bg-[#0369a1] focus:outline-none focus:ring-4 focus:ring-sky-300 active:scale-[0.98]"
                    >
                        Iniciar sesión
                    </button>
                    <p class="text-center text-xs text-slate-600">
                        ¿no tiene una cuenta?
                        <a href="{{ route('register') }}" class="font-semibold text-sky-700 hover:underline">
                            registrese
                        </a>
                    </p>
                </div>
            </form>
        </div>
@endsection