<aside
    class="w-64 flex-shrink-0 border-r border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-[#101922] flex flex-col justify-between p-4">
    <div class="flex flex-col gap-8">
        <div class="flex items-center gap-3 px-2">
            <div class="h-10 w-10 flex items-center justify-center shrink-0">
                <img src="/storage/branding/r9fgbS2xBsVRBkCrVv8B1og3G64GXGvFFxLChkrF.webp"
                    class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <h1 class="text-[#0d141b] dark:text-white text-base font-bold leading-tight">Hotel Andros</h1>
                <p class="text-[#4c739a] text-xs font-medium">Admin Portal</p>
            </div>
        </div>
        <nav class="flex flex-col gap-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span class="material-symbols-outlined">grid_view</span>
                <p class="text-sm font-semibold">Dashboard</p>
            </a>

            <a href="{{ route('admin.bookings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span class="material-symbols-outlined">calendar_today</span>
                <p class="text-sm font-medium">Reservas</p>
            </a>

            @if(auth()->user()->hasAnyRole(['super_admin', 'supervisor']))
                <a href="{{ route('admin.rooms.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.rooms.*') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">bed</span>
                    <p class="text-sm font-medium">Habitaciones</p>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">bar_chart</span>
                    <p class="text-sm font-medium">Reportes</p>
                </a>
            @endif

            @if(auth()->user()->hasRole('super_admin'))
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">group</span>
                    <p class="text-sm font-medium">Usuarios</p>
                </a>

                <a href="{{ route('admin.content.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.content.*') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">article</span>
                    <p class="text-sm font-medium">Editar Contenido</p>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <p class="text-sm font-medium">Configuración</p>
                </a>
            @endif
        </nav>
    </div>
    <div class="flex flex-col gap-4">
        <a href="/" target="_blank"
            class="flex w-full items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold tracking-wide hover:bg-primary/90 transition-all">
            Ver Sitio Web
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 px-3 py-2 text-[#4c739a] hover:text-red-500">
                <span class="material-symbols-outlined">logout</span>
                <p class="text-sm font-medium">Cerrar Sesión</p>
            </button>
        </form>
    </div>
</aside>