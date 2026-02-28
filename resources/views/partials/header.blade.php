<div class="h-16 w-full"></div>
<header
    class="fixed top-0 left-0 right-0 z-[100] w-full bg-white/90 dark:bg-[var(--dark-bg-color)]/90 backdrop-blur-md border-b border-solid border-slate-100 dark:border-slate-800">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="h-10 md:h-12 text-primary flex items-center">
                <img src="{{ $settings['hotel_logo'] ?? '/images/branding/logo.png' }}"
                    class="h-full w-auto object-contain"
                    style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
            </a>
        </div>

        <nav class="hidden md:flex items-center gap-6">
            <a class="text-sm font-medium hover:text-primary transition-colors"
                href="{{ url('/') }}#inicio">{{ __('Inicio') }}</a>
            <a class="text-sm font-medium hover:text-primary transition-colors"
                href="{{ url('/') }}#habitaciones">{{ __('Habitaciones') }}</a>
            <a class="text-sm font-medium hover:text-primary transition-colors"
                href="{{ url('/') }}#cafe-bar">{{ __('Servicios') }}</a>
            <a class="text-sm font-medium hover:text-primary transition-colors"
                href="{{ url('/') }}#contacto">{{ __('Contacto') }}</a>
        </nav>

        <div class="flex items-center gap-4">
            <!-- Theme Toggle -->
            <button id="theme-toggle"
                class="p-2 rounded-full text-slate-400 hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-900 transition-all">
                <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                <span class="material-symbols-outlined block dark:hidden">dark_mode</span>
            </button>

            <div class="flex gap-2 text-xs font-bold">
                <a href="{{ url('/lang/en') }}"
                    class="{{ app()->getLocale() == 'en' ? 'text-primary' : 'text-slate-400' }} hover:text-primary transition-colors">EN</a>
                <span class="text-slate-300">|</span>
                <a href="{{ url('/lang/es') }}"
                    class="{{ app()->getLocale() == 'es' ? 'text-primary' : 'text-slate-400' }} hover:text-primary transition-colors">ES</a>
            </div>

            <!-- Boton de Reserva Solo Movil -->
            <button
                onclick="if(typeof processFastBooking === 'function') { processFastBooking(); } else if(typeof openBookingModal === 'function') { openBookingModal('2', 'Habitación Deluxe', 2); }"
                class="md:hidden bg-primary text-white font-bold text-[10px] px-3 py-1.5 rounded uppercase tracking-wider shadow-sm">
                {{ app()->getLocale() == 'es' ? 'Reservar' : 'Book Now' }}
            </button>
        </div>
    </div>
</header>

<script>
    const themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function () {
        // Toggle icons
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }
    });
</script>