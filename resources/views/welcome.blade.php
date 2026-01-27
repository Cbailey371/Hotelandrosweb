@extends('layouts.app')

@section('title', $settings['hotel_name'] ?? 'LuxeStay Hotel')

@section('content')
    <div class="max-w-[1440px] mx-auto px-4 md:px-8">
        <!-- Alertas -->
        @if(session('success'))
            <div class="fixed top-24 right-8 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl animate-bounce">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="fixed top-24 right-8 z-50 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl animate-pulse">
                <div class="flex flex-col gap-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span class="font-bold text-xs">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Hero Section -->
        <div class="relative pt-8 mb-32" id="inicio">
            <!-- Container with overflow-hidden for the background image -->
            <div class="relative w-full rounded-3xl overflow-hidden bg-cover bg-center flex flex-col justify-center"
                style='min-height: 90vh !important; background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.2) 60%), url("{{ $settings['hero_image'] ?? '/images/branding/hero.png' }}");'>
                <div class="p-10 md:p-20 text-center">
                    <div
                        class="text-white text-5xl md:text-7xl font-black mb-6 tracking-tight drop-shadow-lg leading-tight hero-no-filter">
                        {!! app()->getLocale() == 'es' ? ($settings['hero_title_es'] ?? '') : ($settings['hero_title_en'] ?? '') !!}
                    </div>
                    <div
                        class="text-white text-xl md:text-2xl max-w-3xl mx-auto leading-relaxed drop-shadow-md hero-no-filter">
                        {!! app()->getLocale() == 'es' ? ($settings['hero_subtitle_es'] ?? '') : ($settings['hero_subtitle_en'] ?? '') !!}
                    </div>
                    <div class="mt-12 flex justify-center gap-4">
                        <a href="#habitaciones"
                            class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-xl shadow-primary/30 hover:bg-primary/90 transition-all scale-110 hover:scale-105">{{ __('Descubrir Habitaciones') }}</a>
                    </div>
                </div>
            </div>

            <!-- Availability Bar (MOVE OUTSIDE overflow-hidden) -->
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 flex justify-center translate-y-1/2 z-20">
                <div
                    class="bg-white dark:bg-[#0b0c11] w-full max-w-5xl rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 p-4 md:p-6 grid grid-cols-1 md:grid-cols-4 gap-4 md:divide-x divide-slate-100 dark:divide-slate-800">
                    <div class="flex flex-col px-4">
                        <label
                            class="text-[10px] uppercase tracking-widest font-black text-primary mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">calendar_month</span> Check-In
                        </label>
                        <input type="date" id="search-check-in"
                            class="bg-transparent border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer"
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="flex flex-col px-4">
                        <label
                            class="text-[10px] uppercase tracking-widest font-black text-primary mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">event_repeat</span> Check-Out
                        </label>
                        <input type="date" id="search-check-out"
                            class="bg-transparent border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer"
                            value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="flex flex-col px-4">
                        <label
                            class="text-[10px] uppercase tracking-widest font-black text-primary mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">group</span> {{ __('Huéspedes') }}
                        </label>
                        <select id="search-guests"
                            class="bg-transparent border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer">
                            <option value="1">{{ __('1 Adulto') }}</option>
                            <option value="2" selected>{{ __('2 Adultos') }}</option>
                            <option value="3">{{ __('2 Adultos, 1 Niño') }}</option>
                            <option value="4">{{ __('2 Adultos, 2 Niños') }}</option>
                            <option value="5">{{ __('Más de 4') }}</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-center pl-4">
                        <button onclick="processFastBooking()"
                            class="w-full h-full bg-primary text-white font-black rounded-xl hover:bg-primary/90 transition-all uppercase tracking-widest text-xs py-4 shadow-lg shadow-primary/20">
                            {{ __('Reservar Ahora') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Space for the bar overlap -->
        <div class="h-64 md:h-24"></div>

        <!-- Habitaciones Section -->
        <section id="habitaciones" class="scroll-mt-32 mb-24">
            <div class="flex flex-col items-center text-center mb-16">
                <div
                    class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest mb-4">
                    {!! app()->getLocale() == 'es' ? ($settings['rooms_badge_es'] ?? '') : ($settings['rooms_badge_en'] ?? '') !!}
                </div>
                <div class="text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-800 dark:text-white">
                    {!! app()->getLocale() == 'es' ? ($settings['rooms_title_es'] ?? '') : ($settings['rooms_title_en'] ?? '') !!}
                </div>
                <div class="w-20 h-1.5 bg-primary rounded-full mb-6"></div>
                <div class="text-secondary max-w-2xl text-lg">
                    {!! app()->getLocale() == 'es' ? ($settings['rooms_description_es'] ?? '') : ($settings['rooms_description_en'] ?? '') !!}
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-8">
                @foreach($rooms as $room)
                    <div
                        class="w-full max-w-[400px] bg-white dark:bg-[#0b0c11] rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 group flex flex-col transition-all hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2">
                        <div class="h-72 overflow-hidden relative">
                            <div class="w-full h-full bg-center bg-cover transition-transform duration-1000 group-hover:scale-110"
                                style='background-image: url("{{ $room->image }}");' loading="lazy"></div>
                            <div
                                class="absolute top-4 right-4 px-5 py-3 bg-white/95 backdrop-blur rounded-2xl shadow-xl flex items-center gap-1">
                                <span class="text-green-600 font-black text-xl">${{ number_format($room->price, 0) }}</span>
                                <span class="text-[10px] font-bold text-[#4c739a] uppercase">{{ __('/noche') }}</span>
                            </div>
                        </div>
                        <div class="p-10 flex-1 flex flex-col">
                            <h4 class="text-[28px] font-black mb-2 text-slate-800 dark:text-white leading-tight">
                                {{ app()->getLocale() == 'es' ? $room->name_es : $room->name_en }}
                            </h4>
                            <p class="text-sm font-medium text-[#4c739a] dark:text-slate-400 mb-8">
                                {{ app()->getLocale() == 'es' ? $room->description_es : $room->description_en }}
                            </p>

                            <div class="flex flex-wrap gap-2 mb-8">
                                @foreach(array_slice($room->amenities ?? [], 0, 3) as $amenity)
                                    <div
                                        class="flex items-center gap-2 px-4 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl text-[10px] font-bold text-[#475569] dark:text-slate-300 uppercase tracking-widest">
                                        <span class="material-symbols-outlined text-sm text-green-600 font-bold">check</span>
                                        {{ $amenity }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-auto pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600 text-2xl">group</span>
                                        <span class="text-sm font-black text-slate-800 dark:text-white">{{ $room->capacity }}
                                            {{ __('Pers.') }}</span>
                                    </div>
                                </div>

                                <button
                                    onclick="openBookingModal('{{ $room->id }}', '{{ app()->getLocale() == 'es' ? $room->name_es : $room->name_en }}')"
                                    class="w-full py-4 bg-green-600 text-white text-sm font-black rounded-2xl hover:bg-green-700 transition-all uppercase tracking-widest shadow-lg shadow-green-900/10">
                                    {{ __('Reservar Ahora') }}
                                </button>

                                @if($room->galleries && $room->galleries->count() > 0)
                                    <button
                                        onclick="openGalleryModal({{ $room->galleries->pluck('image_path')->push($room->image)->toJson() }})"
                                        class="w-full py-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all uppercase tracking-normal flex items-center justify-center gap-2 shadow-sm">
                                        <span class="material-symbols-outlined text-xl">photo_library</span>
                                        {{ __('VER GALERÍA') }} ({{ $room->galleries->count() + 1 }})
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Cafe & Bar Section -->
        <section class="mb-24 scroll-mt-24" id="cafe-bar">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-1/2">
                    <div class="relative rounded-[3rem] overflow-hidden aspect-[4/3] shadow-2xl">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] hover:scale-105"
                            style='background-image: url("{{ $settings['cafe_image'] ?? '/images/gallery/bar.png' }}");'
                            loading="lazy">
                        </div>
                        <div class="absolute inset-0 transition-opacity duration-300"
                            style="background: linear-gradient(to top, {{ $settings['cafe_overlay_color'] ?? '#000000' }} 0%, transparent 100%); opacity: {{ ($settings['cafe_overlay_opacity'] ?? 80) / 100 }};">
                        </div>
                        <div class="absolute bottom-10 left-10"
                            style="color: {{ $settings['cafe_text_color'] ?? '#ffffff' }}">
                            @php
                                $cafeBadge = app()->getLocale() == 'es' ? ($settings['cafe_image_badge_es'] ?? '') : ($settings['cafe_image_badge_en'] ?? '');
                                $cafeTitle = app()->getLocale() == 'es' ? ($settings['cafe_image_title_es'] ?? '') : ($settings['cafe_image_title_en'] ?? '');
                            @endphp

                            @if($cafeBadge)
                                <p class="text-xs font-bold tracking-[0.3em] uppercase mb-2 opacity-80">
                                    {{ $cafeBadge }}
                                </p>
                            @endif

                            @if($cafeTitle)
                                <h3 class="text-4xl font-black tracking-tight">
                                    {{ $cafeTitle }}
                                </h3>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <div
                        class="inline-block px-4 py-1.5 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-full mb-6 uppercase tracking-widest">
                        ANDROS CAFE</div>
                    <h2 class="text-4xl md:text-5xl font-black mb-8 tracking-tight leading-tight">
                        {{ app()->getLocale() == 'es' ? ($settings['cafe_title_es'] ?? __('Sabores Artesanales & Coctelería')) : ($settings['cafe_title_en'] ?? __('Artisan Flavors & Cocktails')) }}
                    </h2>
                    <p class="text-lg text-secondary dark:text-slate-400 mb-10 leading-relaxed">
                        {!! app()->getLocale() == 'es' ? ($settings['cafe_description_es'] ?? __('Desde el espresso matutino hasta cócteles de autor, nuestros mixólogos y chefs crean momentos inolvidables en un ambiente sofisticado.')) : ($settings['cafe_description_en'] ?? __('From morning espresso to signature cocktails, our mixologists and chefs create unforgettable moments in a sophisticated atmosphere.')) !!}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div
                            class="flex items-start gap-4 p-6 bg-white dark:bg-[#0b0c11] rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                            <div class="w-12 h-12 shrink-0 bg-primary/10 rounded-xl flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-primary text-2xl">{{ $settings['cafe_feature1_icon'] ?? 'coffee' }}</span>
                            </div>
                            <div>
                                <h4 class="font-black text-lg mb-1">
                                    {!! app()->getLocale() == 'es' ? ($settings['cafe_feature1_title_es'] ?? __('Especialidad')) : ($settings['cafe_feature1_title_en'] ?? __('Specialty')) !!}
                                </h4>
                                <p class="text-sm text-secondary">
                                    {!! app()->getLocale() == 'es' ? ($settings['cafe_feature1_desc_es'] ?? __('Granos seleccionados y tostado artesanal.')) : ($settings['cafe_feature1_desc_en'] ?? __('Selected beans and artisan roasting.')) !!}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-start gap-4 p-6 bg-white dark:bg-[#0b0c11] rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                            <div class="w-12 h-12 shrink-0 bg-amber-100 rounded-xl flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-amber-600 text-2xl">{{ $settings['cafe_feature2_icon'] ?? 'restaurant' }}</span>
                            </div>
                            <div>
                                <h4 class="font-black text-lg mb-1">
                                    {!! app()->getLocale() == 'es' ? ($settings['cafe_feature2_title_es'] ?? __('Coctelería')) : ($settings['cafe_feature2_title_en'] ?? __('Cocktails')) !!}
                                </h4>
                                <p class="text-sm text-secondary">
                                    {!! app()->getLocale() == 'es' ? ($settings['cafe_feature2_desc_es'] ?? __('Mixología moderna con toques locales.')) : ($settings['cafe_feature2_desc_en'] ?? __('Modern mixology with local touches.')) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Ubicación Section -->
        <section class="mb-24 scroll-mt-24" id="contacto">
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
                <div class="w-full lg:w-1/2">
                    <div
                        class="relative rounded-[3rem] overflow-hidden aspect-video shadow-2xl border-8 border-white dark:border-slate-800">
                        <iframe
                            src="{{ $settings['google_maps_iframe'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3932.747!2d-79.90!3d9.35!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOcKwMjEnMDAuMCJOIDc5wrA1NCc0MC4wIlc!5e0!3m2!1ses!2spa!4v1627000000000!5m2!1ses!2spa' }}"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            class="grayscale hover:grayscale-0 transition-all duration-700"></iframe>
                        <div
                            class="absolute bottom-6 left-6 right-6 bg-white/90 backdrop-blur p-6 rounded-2xl shadow-xl border border-white">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-primary uppercase text-[10px] tracking-widest mb-1">
                                        {{ __('Dirección del Hotel') }}
                                    </h4>
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $settings['hotel_address'] ?? 'Ave. Herrera, Colón, Panamá' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <span
                        class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest mb-4">
                        {{ app()->getLocale() == 'es' ? ($settings['location_badge_es'] ?? __('Donde estamos ubicados')) : ($settings['location_badge_en'] ?? __('Donde estamos ubicados')) }}
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black mb-8 tracking-tight leading-tight">
                        {!! app()->getLocale() == 'es' ? ($settings['location_title_es'] ?? 'Explore the Gateway of the Americas') : ($settings['location_title_en'] ?? 'Explore the Gateway of the Americas') !!}
                    </h2>
                    <p class="text-lg text-secondary dark:text-slate-400 mb-10 leading-relaxed">
                        {!! app()->getLocale() == 'es'
        ? ($settings['location_description_es'] ?? 'Ubicado en la vibrante costa caribeña, ' . ($settings['hotel_name'] ?? 'Hotel Andros') . ' es el punto de partida perfecto para explorar el Canal de Panamá.')
        : ($settings['location_description_en'] ?? 'Located on the vibrant Caribbean coast, ' . ($settings['hotel_name'] ?? 'Hotel Andros') . ' serves as your perfect base for exploring the Panama Canal.') !!}
                    </p>
                </div>
            </div>
        </section>

        <!-- Carousel Premium Section (Home Showcase) -->
        @if($carouselImages && $carouselImages->count() > 0)
            <section class="mb-0 scroll-mt-24 overflow-hidden px-4 md:px-0" id="galeria-destacada">
                <div class="max-w-6xl mx-auto mb-10 text-center md:text-left">
                    <span
                        class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-[0.2em] mb-4">
                        {{ app()->getLocale() == 'es' ? ($settings['carousel_badge_es'] ?? 'Visual Experience') : ($settings['carousel_badge_en'] ?? 'Visual Experience') }}
                    </span>
                    <h2
                        class="text-3xl md:text-5xl font-black mt-4 tracking-tight drop-shadow-sm text-slate-800 dark:text-white">
                        {{ app()->getLocale() == 'es' ? ($settings['carousel_title_es'] ?? 'Galería de Momentos') : ($settings['carousel_title_en'] ?? 'Moments Gallery') }}
                    </h2>
                </div>

                <div class="swiper home-carousel !overflow-visible">
                    <div class="swiper-wrapper">
                        @foreach($carouselImages as $item)
                            <div class="swiper-slide !w-[300px] md:!w-[500px]">
                                <div
                                    class="relative aspect-video rounded-[3rem] overflow-hidden shadow-2xl shadow-primary/5 group cursor-grab active:cursor-grabbing border-4 border-white dark:border-slate-800">
                                    <img src="{{ $item->image_path }}"
                                        class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-110">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Navigation -->
                    <div class="flex justify-center md:justify-end gap-3 mt-4 max-w-6xl mx-auto px-10 relative z-20">
                        <button
                            class="swiper-prev w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-primary shadow-xl border border-slate-100 dark:border-slate-700 hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined font-black">chevron_left</span>
                        </button>
                        <button
                            class="swiper-next w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-primary shadow-xl border border-slate-100 dark:border-slate-700 hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined font-black">chevron_right</span>
                        </button>
                    </div>
                </div>
            </section>
        @endif

        <!-- Atractivos Turísticos Section -->
        <section class="mb-24 scroll-mt-24" id="atractivos">
            <div class="text-center mb-16">
                <span
                    class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest mb-4">
                    {{ app()->getLocale() == 'es' ? ($settings['attractions_badge_es'] ?? 'EXPLORE PANAMA') : ($settings['attractions_badge_en'] ?? 'EXPLORE PANAMA') }}
                </span>
                <h2 class="text-4xl md:text-5xl font-black tracking-tight">
                    {{ app()->getLocale() == 'es' ? ($settings['attractions_title_es'] ?? __('Local Attractions')) : ($settings['attractions_title_en'] ?? __('Local Attractions')) }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($attractions as $attraction)
                    <div
                        class="bg-white dark:bg-[#0b0c11] rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all group">
                        <div class="h-64 overflow-hidden relative">
                            <img src="{{ $attraction->image_path }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                        <div class="p-8">
                            <h4 class="text-2xl font-black mb-4">
                                {!! app()->getLocale() == 'es' ? $attraction->title_es : $attraction->title_en !!}
                            </h4>
                            <div class="text-secondary dark:text-slate-400 text-sm leading-relaxed mb-6">
                                {!! app()->getLocale() == 'es' ? $attraction->description_es : $attraction->description_en !!}
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </section>

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

                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-secondary mb-3">{{ __('Teléfono (Opcional)') }}</label>
                            <input type="text" name="phone" placeholder="{{ __('+507 ...') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                        </div>

                        <div>
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

        <!-- Gallery Modal -->
        <div id="gallery-modal" class="fixed inset-0 z-[110] hidden bg-black/95 backdrop-blur-xl flex flex-col">
            <!-- Toolbar -->
            <div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center z-20 text-white">
                <span class="text-xs font-bold tracking-widest uppercase opacity-70" id="gallery-counter">1 / 1</span>
                <button onclick="closeGalleryModal()"
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-all">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Main Image -->
            <div class="flex-1 min-h-0 flex items-center justify-center p-4 md:p-8 relative">
                <button onclick="prevImage()"
                    class="absolute left-4 md:left-8 p-4 rounded-full bg-black/60 hover:bg-white/20 text-white transition-all z-30 shadow-2xl backdrop-blur-md">
                    <span class="material-symbols-outlined text-3xl font-black">chevron_left</span>
                </button>

                <div class="w-full h-full flex items-center justify-center overflow-hidden">
                    <img id="gallery-main-image" src=""
                        class="max-h-full max-w-full w-auto h-auto object-contain rounded-2xl shadow-2xl animate-in fade-in zoom-in duration-500 border-2 border-white/20">
                </div>

                <button onclick="nextImage()"
                    class="absolute right-4 md:right-8 p-4 rounded-full bg-black/60 hover:bg-white/20 text-white transition-all z-30 shadow-2xl backdrop-blur-md">
                    <span class="material-symbols-outlined text-3xl font-black">chevron_right</span>
                </button>
            </div>

            <!-- Thumbnails -->
            <div class="h-24 md:h-32 bg-black/40 p-4 flex justify-center gap-2 overflow-x-auto" id="gallery-thumbnails">
                <!-- Thumbnails inserted via JS -->
            </div>
        </div>

        <script>
       function processFastBooking() {
                    const guestCount = parseInt(document.getElementById('search-guests').value);
                    let roomId, roomName;

                    if (guestCount <= 2) {
                        roomId = '2'; // Deluxe con cama extragrande
                        roomName = '{{ app()->getLocale() == "es" ? "Habitación Deluxe con cama extragrande" : "Deluxe Room with Extra Large Bed" }}';
                    } else {
                        roomId = '1'; // Doble Deluxe
                        roomName = '{{ app()->getLocale() == "es" ? "Habitación Doble Deluxe" : "Double Deluxe Room" }}';
                    }

                    openBookingModal(roomId, roomName);
                }

                function scrollToRooms() {
                    document.getElementById('habitaciones').scrollIntoView({ behavior: 'smooth' });
                }

                function openBookingModal(roomId, roomName) {
                    // Sincronizar con la barra de búsqueda
                    const checkIn = document.getElementById('search-check-in').value;
                    const checkOut = document.getElementById('search-check-out').value;
                    const guests = document.getElementById('search-guests').value;

                    document.getElementById('modal-room-id').value = roomId;
                    document.getElementById('modal-title').innerText = roomName;
                    document.getElementById('modal-check-in').value = checkIn;
                    document.getElementById('modal-check-out').value = checkOut;
                    document.getElementById('modal-guests').value = guests;

                    document.getElementById('booking-modal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }

                function closeBookingModal() {
                    document.getElementById('booking-modal').classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }

                // Gallery Logic
                let currentGalleryImages = [];
                let currentImageIndex = 0;

                function openGalleryModal(images) {
                    currentGalleryImages = images;
                    currentImageIndex = 0;

                    document.getElementById('gallery-modal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    updateGalleryUI();
                }

                function closeGalleryModal() {
                    document.getElementById('gallery-modal').classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }

                function updateGalleryUI() {
                    // Update Main Image
                    const mainImage = document.getElementById('gallery-main-image');
                    mainImage.src = currentGalleryImages[currentImageIndex];

                    // Update Counter
                    document.getElementById('gallery-counter').innerText = `${currentImageIndex + 1} / ${currentGalleryImages.length}`;

                    // Update Thumbnails
                    const thumbsContainer = document.getElementById('gallery-thumbnails');
                    thumbsContainer.innerHTML = '';

                    currentGalleryImages.forEach((img, index) => {
                        const thumb = document.createElement('img');
                        thumb.src = img;
                        thumb.className = `h-full aspect-square object-cover rounded-md cursor-pointer border-2 transition-all ${index === currentImageIndex ? 'border-primary opacity-100' : 'border-transparent opacity-50 hover:opacity-100'}`;
                        thumb.onclick = () => {
                            currentImageIndex = index;
                            updateGalleryUI();
                        };
                        thumbsContainer.appendChild(thumb);
                    });
                }

                function nextImage() {
                    currentImageIndex = (currentImageIndex + 1) % currentGalleryImages.length;
                    updateGalleryUI();
                }

                function prevImage() {
                    currentImageIndex = (currentImageIndex - 1 + currentGalleryImages.length) % currentGalleryImages.length;
                    updateGalleryUI();
                }

                // Keyboard Support
                document.addEventListener('keydown', function (event) {
                    if (document.getElementById('gallery-modal').classList.contains('hidden')) return;

                    if (event.key === 'Escape') closeGalleryModal();
                    if (event.key === 'ArrowRight') nextImage();
                    if (event.key === 'ArrowLeft') prevImage();
                });

                // Initialize Home Carousel
                document.addEventListener('DOMContentLoaded', function () {
                    new Swiper('.home-carousel', {
                        slidesPerView: 'auto',
                        centeredSlides: true,
                        spaceBetween: 20,
                        loop: true,
                        autoHeight: true,
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                        },
                        navigation: {
                            nextEl: '.swiper-next',
                            prevEl: '.swiper-prev',
                        },
                        effect: 'coverflow',
                        coverflowEffect: {
                            rotate: 5,
                            stretch: 0,
                            depth: 100,
                            modifier: 1,
                            slideShadows: false,
                        },
                    });
                });
            </script>
        </div>
@endsection