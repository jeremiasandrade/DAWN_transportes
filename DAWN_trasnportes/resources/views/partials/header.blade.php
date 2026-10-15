<header class="flex w-full items-center justify-between p-4 md:px-12 bg-sky-100 shadow-md">
    <div class="text-2xl font-bold text-slate-800">
        Dawn Transportes
    </div>
    
    <!-- Spacer in the middle -->
    <div class="flex-1"></div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-4">
        <button 
            class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:text-sky-600 hover:shadow"
            aria-label="Buscar destino"
        >
            <!-- SVG para Search -->
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>

        <button 
            class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:text-sky-600 hover:shadow"
            aria-label="Colectivos"
        >
            <!-- SVG para Bus -->
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 6v6"></path>
                <path d="M15 6v6"></path>
                <path d="M2 12h19.6"></path>
                <path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"></path>
                <circle cx="7" cy="18" r="2"></circle>
                <path d="M9 18h5"></path>
                <circle cx="16" cy="18" r="2"></circle>
            </svg>
        </button>

        <button 
            class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-white text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:text-sky-600 hover:shadow"
            aria-label="Perfil de usuario"
        >
            <!-- SVG para User -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </button>
    </div>
</header>