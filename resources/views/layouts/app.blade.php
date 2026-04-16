<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="{{ ($settings['website_theme'] ?? 'light') == 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $settings['primary_color'] ?? '#137fec' }}">

    <meta name="description"
        content="{{ 
            app()->getLocale() == 'en' 
            ? ($settings['website_description_en'] ?? $settings['website_description'] ?? 'High-quality accommodation in the heart of Colón.') 
            : ($settings['website_description'] ?? 'Hospedaje de calidad en el corazón de Colón.') 
        }}">
    <meta name="keywords" 
        content="{{ 
            app()->getLocale() == 'en' 
            ? ($settings['website_keywords_en'] ?? $settings['website_keywords'] ?? 'hotel, colon, panama, andros') 
            : ($settings['website_keywords'] ?? 'hotel, colon, panama, andros') 
        }}">
    <meta name="author" content="{{ $settings['hotel_name'] ?? 'Hotel Andros' }}">

    <title>@yield('title') - {{ $settings['hotel_name'] ?? config('app.name', 'Laravel') }}</title>

    <!-- Manifest PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/x-icon"
        href="{{ $settings['hotel_favicon'] ?? $settings['hotel_logo'] ?? '/favicon.ico' }}">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;700;900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Montserrat:wght@100;300;400;700;900&family=Roboto:wght@300;400;700;900&family=Merriweather:wght@300;400;700;900&family=Oswald:wght@400;700&family=Lora:wght@400;700&family=Dancing+Script:wght@400;700&family=Nunito:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- App Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --primary-color:
                {{ $settings['primary_color'] ?? '#137fec' }}
            ;
            --secondary-color:
                {{ $settings['secondary_color'] ?? '#4c739a' }}
            ;
            --contrast-level:
                {{ ($settings['text_contrast_level'] ?? 50) / 100 }}
            ;
            --dark-bg-color:
                {{ $settings['dark_mode_color'] ?? '#06070a' }}
            ;
        }

        @if(($settings['high_contrast'] ?? '0') == '1')
            :root {
                --contrast-level: 1;
            }

        @endif

        /* Dynamic Contrast Adjustments */
        .text-slate-600, .text-slate-500, .text-slate-800, .text-secondary, .text-primary, p:not(.hero-no-filter), span:not(.hero-no-filter), h1:not(.hero-no-filter), h2:not(.hero-no-filter), h3:not(.hero-no-filter), h4:not(.hero-no-filter), h5:not(.hero-no-filter), h6:not(.hero-no-filter) {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 - var(--contrast-level) * 0.3));
        }

        .hero-no-filter {
            filter: none !important;
        }

        .dark .text-slate-400:not(.hero-no-filter),
        .dark .text-slate-300:not(.hero-no-filter),
        .dark .text-slate-50:not(.hero-no-filter),
        .dark .text-secondary:not(.hero-no-filter),
        .dark .text-white:not(.hero-no-filter),
        .dark p:not(.hero-no-filter),
        .dark span:not(.hero-no-filter),
        .dark h1:not(.hero-no-filter),
        .dark h2:not(.hero-no-filter),
        .dark h3:not(.hero-no-filter),
        .dark h4:not(.hero-no-filter),
        .dark h5:not(.hero-no-filter),
        .dark h6:not(.hero-no-filter) {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 + var(--contrast-level) * 0.4));
        }

        /* High Contrast Mode specific overrides */
        @if(($settings['high_contrast'] ?? '0') == '1')
            .text-slate-600,
            .text-slate-500,
            .text-slate-800,
            .text-secondary,
            .text-primary,
            p,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                color: #000 !important;
            }

            .dark .text-slate-400,
            .dark .text-slate-300,
            .dark .text-slate-50,
            .dark .text-secondary,
            .dark .text-white,
            .dark p,
            .dark h1,
            .dark h2,
            .dark h3,
            .dark h4,
            .dark h5,
            .dark h6 {
                color: #fff !important;
            }

        @endif

        /* Theme backgrounds */
        .dark body {
            background-color: var(--dark-bg-color) !important;
        }

        /* Quill Pixel Sizes */
        .ql-size-12px {
            font-size: 12px;
        }

        .ql-size-14px {
            font-size: 14px;
        }

        .ql-size-16px {
            font-size: 16px;
        }

        .ql-size-18px {
            font-size: 18px;
        }

        .ql-size-20px {
            font-size: 20px;
        }

        .ql-size-24px {
            font-size: 24px;
        }

        .ql-size-30px {
            font-size: 30px;
        }

        .ql-size-36px {
            font-size: 36px;
        }

        .ql-size-48px {
            font-size: clamp(24px, 6vw, 48px);
        }

        .ql-size-60px {
            font-size: clamp(28px, 8vw, 60px);
        }

        .ql-size-72px {
            font-size: clamp(32px, 10vw, 72px);
        }

        .ql-size-84px {
            font-size: clamp(36px, 12vw, 84px);
        }

        .ql-size-96px {
            font-size: clamp(40px, 14vw, 96px);
        }

        .ql-size-128px {
            font-size: clamp(44px, 16vw, 128px);
        }

        .hero-content {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        @media (max-width: 767px) {

            /* Overrides Forzosos para los Inline Styles de Quill en móviles con altísima especificidad absoluta (ID) */
            html body #mobile-hero-title,
            html body #mobile-hero-title *,
            html body #mobile-cafe-title,
            html body #mobile-cafe-title *,
            html body #mobile-contact-title,
            html body #mobile-contact-title *,
            html body #mobile-contact-subtitle,
            html body #mobile-contact-subtitle *,
            html body #mobile-cafe-subtitle,
            html body #mobile-cafe-subtitle * {
                white-space: normal !important;
                text-align: center !important;
                word-wrap: break-word !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                max-width: 100% !important;
                width: 100% !important;
                transform: none !important;
            }

            html body #mobile-hero-title,
            html body #mobile-hero-title * {
                font-size: clamp(1.8rem, 8vw, 3.5rem) !important;
                line-height: 1.1 !important;
                padding: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            html body #mobile-hero-subtitle,
            html body #mobile-hero-subtitle * {
                font-size: clamp(1.1rem, 6vw, 1.8rem) !important;
                line-height: 1.4 !important;
                white-space: normal !important;
                text-align: center !important;
                word-wrap: break-word !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
                width: 100% !important;
                margin-top: 0 !important;
                transform: none !important;
            }

            html body #mobile-cafe-title,
            html body #mobile-cafe-title * {
                font-size: clamp(1.8rem, 8vw, 3rem) !important;
                line-height: 1.1 !important;
            }

            html body #mobile-contact-title,
            html body #mobile-contact-title * {
                font-size: clamp(2rem, 9vw, 3.5rem) !important;
                line-height: 1.1 !important;
            }
        }
        }

        /* Override para Habitaciones y Tarifas Header */
        .rooms-title-container {
            font-size: clamp(2rem, 8vw, 3rem) !important;
            line-height: 1.2 !important;
        }
        }

        /* KILL EMPTY PARAGRAPHS FROM QUILL GLOBALLY IN TITLES */
        html body #mobile-hero-title p:empty,
        html body #mobile-hero-subtitle p:empty,
        html body #mobile-cafe-title p:empty,
        html body #mobile-cafe-subtitle p:empty,
        html body #mobile-contact-title p:empty,
        html body #mobile-contact-subtitle p:empty,
        html body #mobile-hero-title p br:only-child,
        html body #mobile-hero-subtitle p br:only-child,
        html body #mobile-cafe-title p br:only-child,
        html body #mobile-cafe-subtitle p br:only-child,
        html body #mobile-contact-title p br:only-child,
        html body #mobile-contact-subtitle p br:only-child {
            display: none !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 0 !important;
        }

        .dark header {
            background-color: var(--dark-bg-color) !important;
            opacity: 0.95;
        }

        .dark .bg-white {
            background-color: color-mix(in srgb, var(--dark-bg-color), white 5%) !important;
        }

        .dark .bg-slate-50 {
            background-color: color-mix(in srgb, var(--dark-bg-color), white 5%) !important;
        }

        .dark .border-slate-100 {
            border-color: color-mix(in srgb, var(--dark-bg-color), white 10%) !important;
        }
    </style>
    @stack('styles')

    <script>
        // Init Theme to prevent FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="dark:bg-[#06070a] text-[#0d141b] dark:text-slate-50 min-h-screen overflow-x-hidden w-full"
    style="--dark-bg-color: {{ $settings['dark_mode_color'] ?? '#06070a' }}">
    @include('partials.header')

    <!-- Popup Promocional -->
    @if(isset($activePromotion))
        <div x-data="{ 
                                                showPromo: false, 
                                                promoId: 'promo_viewed_{{ $activePromotion->id }}',
                                                init() {
                                                    // Solo mostrar si no se ha visto en esta sesión
                                                    if (!sessionStorage.getItem(this.promoId)) {
                                                        setTimeout(() => { this.showPromo = true; }, 1000); 
                                                    }
                                                },
                                                closePromo() {
                                                    this.showPromo = false;
                                                    sessionStorage.setItem(this.promoId, 'true');
                                                }
                                             }" x-show="showPromo"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

            <div class="relative w-full max-w-4xl mx-auto" @click.away="closePromo()">
                <!-- Botón Cerrar Flotante -->
                <button @click="closePromo()"
                    class="absolute -top-10 right-0 md:-right-10 text-white hover:text-gray-300 transition-colors z-10 w-8 h-8 flex items-center justify-center bg-black/40 rounded-full">
                    <span class="material-symbols-outlined font-bold text-xl">close</span>
                </button>

                <div
                    class="bg-transparent overflow-hidden rounded-xl shadow-2xl relative group {{ $activePromotion->media_type === 'youtube' ? 'aspect-video' : '' }}">

                    @if($activePromotion->media_type === 'youtube')
                        <iframe
                            src="https://www.youtube.com/embed/{{ $activePromotion->youtube_id }}?autoplay=1&mute=1&loop=1&color=white&controls=0&modestbranding=1&playsinline=1&rel=0&playlist={{ $activePromotion->youtube_id }}"
                            title="{{ $activePromotion->title }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen class="w-full h-full absolute top-0 left-0"></iframe>

                        @if($activePromotion->link_url)
                            <!-- Overlay clickeable transparente para videos si hay link -->
                            <a href="{{ $activePromotion->link_url }}" target="_blank" @click="closePromo()"
                                class="absolute inset-0 z-20"></a>
                        @endif
                    @elseif($activePromotion->media_type === 'video')
                        <video class="w-full h-auto object-contain max-h-[85vh]" autoplay loop muted playsinline>
                            <source src="{{ $activePromotion->media_path }}"
                                type="video/{{ pathinfo($activePromotion->media_path, PATHINFO_EXTENSION) }}">
                            Tu navegador no soporta videos HTML5.
                        </video>
                        @if($activePromotion->link_url)
                            <!-- Overlay clickeable transparente para videos nativos si hay link -->
                            <a href="{{ $activePromotion->link_url }}" target="_blank" @click="closePromo()"
                                class="absolute inset-0 z-20"></a>
                        @endif
                    @else
                        <!-- Imagen Tradicional -->
                        @if($activePromotion->link_url)
                            <a href="{{ $activePromotion->link_url }}" target="_blank" @click="closePromo()">
                        @endif

                            <img src="{{ $activePromotion->media_path }}" alt="{{ $activePromotion->title }}"
                                class="w-full h-auto object-contain max-h-[85vh]">

                            @if($activePromotion->link_url)
                                </a>
                            @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
    <!-- Fin Popup Promocional -->

    <main>
        @yield('content')
    </main>

    @unless($disableFooter ?? false)
        @include('partials.footer')
    @endunless

    <!-- Booking Modal (Premium Responsive) -->
    <div id="booking-modal"
        class="fixed inset-0 z-[100] hidden flex items-start md:items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md overflow-y-auto">
        <div
            class="bg-white dark:bg-[#0b0c11] w-full max-w-xl rounded-[2.5rem] flex flex-col max-h-[92vh] md:max-h-[85vh] overflow-hidden shadow-2xl animate-in zoom-in duration-300 border border-white/20 mt-4 md:mt-0">
            <div
                class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-primary text-white shrink-0">
                <div>
                    <h3 class="text-xl md:text-2xl font-black uppercase tracking-tight" id="modal-title">
                        {{ __('Confirmar Solicitud') }}
                    </h3>
                    <p class="text-white/80 text-[10px] font-bold mt-1 uppercase tracking-widest leading-none">
                        {{ __('Enviando datos a recepción') }}
                    </p>
                </div>
                <button onclick="closeBookingModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition-all">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
            <div class="p-6 md:p-10 overflow-y-auto flex-1 custom-scrollbar">
                <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="room_id" id="modal-room-id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Tu Nombre') }}</label>
                            <input type="text" name="customer_name" placeholder="{{ __('Ej. Juan Pérez') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                required>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Email de Contacto') }}</label>
                            <input type="email" name="email" required placeholder="juan@ejemplo.com"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Desde') }}</label>
                            <input type="date" name="check_in" id="modal-check-in" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Hasta') }}</label>
                            <input type="date" name="check_out" id="modal-check-out" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Huéspedes') }}</label>
                            <select name="guests" id="modal-guests" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                                <option value="1">{{ __('1 Persona') }}</option>
                                <option value="2">{{ __('2 Personas') }}</option>
                                <option value="3">{{ __('3 Personas') }}</option>
                                <option value="4">{{ __('4 Personas') }}</option>
                                <option value="5">{{ __('Más de 4') }}</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('País de Origen') }}</label>
                            <input type="text" name="country" placeholder="{{ __('Ej. Panamá') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Teléfono (Opcional)') }}</label>
                            <input type="text" name="phone" placeholder="{{ __('+507 ...') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Cant. Habitaciones') }}</label>
                            <select name="number_of_rooms" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                                <option value="1">{{ __('1 Habitación') }}</option>
                                <option value="2">{{ __('2 Habitaciones') }}</option>
                                <option value="3">{{ __('3 Habitaciones') }}</option>
                                <option value="4">{{ __('4 Habitaciones') }}</option>
                                <option value="5">{{ __('5 o más') }}</option>
                            </select>
                        </div>
                    </div>

                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Requerimientos Especiales') }}</label>
                    <textarea name="message" rows="3"
                        placeholder="{{ __('Ej. Alergias, hora de llegada, cama extra...') }}"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"></textarea>
            </div>

            <button type="submit"
                class="w-full py-5 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-[0.2em] text-sm">
                {{ __('Confirmar Solicitud de Reserva') }}
            </button>

            <p class="text-[10px] text-center text-secondary uppercase font-bold tracking-widest">
                {{ __('Un asesor te contactará para confirmar disponibilidad y costos totales') }}
            </p>
            </form>
        </div>
    </div>
    </div>

    <!-- Service Worker Registration -->
    <script>
        function openBookingModal(roomId = 'General', roomName = '{{ __("Reservación General") }}', guestCapacity = null) {
            document.getElementById('modal-room-id').value = roomId;
            document.getElementById('modal-title').innerText = roomName;

            // Optional: try to fetch values from search bar if it exists
            const checkInEl = document.getElementById('search-check-in');
            const checkOutEl = document.getElementById('search-check-out');
            if (checkInEl && checkOutEl) {
                document.getElementById('modal-check-in').value = checkInEl.value;
                document.getElementById('modal-check-out').value = checkOutEl.value;
            }

            if (guestCapacity) {
                const guestsSelect = document.getElementById('modal-guests');
                if (guestsSelect) {
                    guestsSelect.innerHTML = ''; // Clear existing
                    for (let i = 1; i <= guestCapacity; i++) {
                        let opt = document.createElement('option');
                        opt.value = i;
                        opt.text = i === 1 ? '{{ __("1 Persona") }}' : i + ' {{ __("Personas") }}';
                        guestsSelect.appendChild(opt);
                    }

                    // Add "+1 Adicional" specifically for the 4-person room capacity
                    if (guestCapacity == 4) {
                        let extraOpt = document.createElement('option');
                        extraOpt.value = 5;
                        extraOpt.text = '4 {{ __("Personas") }} + 1 {{ __("Adicional") }}';
                        guestsSelect.appendChild(extraOpt);
                    }

                    guestsSelect.value = guestCapacity;
                }
            } else {
                const guestsSelect = document.getElementById('modal-guests');
                if (guestsSelect) {
                    guestsSelect.innerHTML = `
                        <option value="1">{{ __('1 Persona') }}</option>
                        <option value="2">{{ __('2 Personas') }}</option>
                        <option value="3">{{ __('3 Personas') }}</option>
                        <option value="4">{{ __('4 Personas') }}</option>
                        <option value="5">{{ __('Más de 4') }}</option>
                    `;
                }
            }

            document.getElementById('booking-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingModal() {
            const bookingModal = document.getElementById('booking-modal');
            if (bookingModal) {
                bookingModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    @stack('scripts')
</body>

</html>