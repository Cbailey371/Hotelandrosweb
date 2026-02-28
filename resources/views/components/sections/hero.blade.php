@props(['data', 'mode' => 'public', 'index' => null])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';

    // Default values
    $overlayOpacity = $data['overlay_opacity'] ?? 50;
    $bgBlur = $data['bg_blur'] ?? 0;
    $paddingY = $data['padding_y'] ?? 100; // Default padding
    $containerWidth = ($data['container_width'] ?? 'boxed') === 'full' ? 'max-w-full px-10' : 'container mx-auto px-6';
    $bgImage = $data['bg_image'] ?? '/images/branding/hero.png';
@endphp

<div class="relative mb-8" id="inicio" style="padding-top: {{ $paddingY }}px; padding-bottom: {{ $paddingY }}px;"
    @if($isEditor)
        :style="{ paddingTop: (getSectionData(getSectionIndex($el))?.padding_y || {{ $paddingY }}) + 'px', paddingBottom: (getSectionData(getSectionIndex($el))?.padding_y || {{ $paddingY }}) + 'px' }"
    @endif>

    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0">
        <img src="{{ $bgImage }}" width="1920" height="1080"
            alt="{{ app()->getLocale() == 'es' ? ($data['title_es'] ?? 'Hotel Andros') : ($data['title_en'] ?? 'Hotel Andros') }}"
            class="w-full h-full object-cover transition-all duration-300 {{ $isEditor ? 'cursor-pointer hover:ring-4 ring-blue-500/50' : '' }}"
            @if($isEditor) @click.stop="selectBackground()" :style="{
                filter: 'blur(' + (getSectionData(getSectionIndex($el))?.bg_blur || 0) + 'px)'
            }" @else style="filter: blur({{ $bgBlur }}px);" @endif data-type="image" data-field="bg_image">
    </div>

    <!-- Overlay Layer -->
    <div class="absolute inset-0 z-0 bg-black transition-opacity duration-300 pointer-events-none" @if($isEditor)
    :style="{ opacity: (getSectionData(getSectionIndex($el))?.overlay_opacity || 0) / 100 }" @endif
        style="opacity: {{ $overlayOpacity / 100 }};"></div>

    <!-- Content Container -->
    <div class="{{ $containerWidth }} relative z-10 flex flex-col items-center justify-center text-center h-full min-h-[400px]"
        @if($isEditor) :style="{ gap: (getSectionData(getSectionIndex($el))?.gap || 24) + 'px' }" @endif
        style="gap: {{ $data['gap'] ?? 24 }}px;">

        <!-- Hero Title -->
        @php
            $locale = app()->getLocale();
            $otherLocale = $locale === 'es' ? 'en' : 'es';
            $titleField = 'title_' . $locale;
            $otherTitleField = 'title_' . $otherLocale;

            $titleStyles = [
                'color' => ($data[$titleField . '_color'] ?? '') ?: ($data[$otherTitleField . '_color'] ?? '') ?: 'inherit',
                'fontSize' => ($data[$titleField . '_fontSize'] ?? '') ?: ($data[$otherTitleField . '_fontSize'] ?? '') ?: 'inherit',
                'fontFamily' => ($data[$titleField . '_fontFamily'] ?? '') ?: ($data[$otherTitleField . '_fontFamily'] ?? '') ?: 'inherit',
                'fontWeight' => ($data[$titleField . '_fontWeight'] ?? '') ?: ($data[$otherTitleField . '_fontWeight'] ?? '') ?: 'normal',
                'textAlign' => ($data[$titleField . '_textAlign'] ?? '') ?: ($data[$otherTitleField . '_textAlign'] ?? '') ?: 'center',
                'letterSpacing' => ($data[$titleField . '_letterSpacing'] ?? '') ?: ($data[$otherTitleField . '_letterSpacing'] ?? '') ?: 'normal',
                'lineHeight' => ($data[$titleField . '_lineHeight'] ?? '') ?: ($data[$otherTitleField . '_lineHeight'] ?? '') ?: '1.2',
                'marginTop' => ($data[$titleField . '_marginTop'] ?? '') ?: ($data[$otherTitleField . '_marginTop'] ?? '') ?: '0px',
                'marginBottom' => ($data[$titleField . '_marginBottom'] ?? '') ?: ($data[$otherTitleField . '_marginBottom'] ?? '') ?: '0px',
                'transform' => "translate(" . (($data[$titleField . '_translateX'] ?? '') ?: ($data[$otherTitleField . '_translateX'] ?? '') ?: 0) . "px, " . (($data[$titleField . '_translateY'] ?? '') ?: ($data[$otherTitleField . '_translateY'] ?? '') ?: 0) . "px)"
            ];
            $titleStyleStr = collect($titleStyles)->map(function ($v, $k) {
                if ($k === 'transform')
                    return "transform: $v";
                $prop = str_replace(['fontSize', 'fontFamily', 'fontWeight', 'textAlign', 'letterSpacing', 'lineHeight', 'marginTop', 'marginBottom'], ['font-size', 'font-family', 'font-weight', 'text-align', 'letter-spacing', 'line-height', 'margin-top', 'margin-bottom'], $k);
                $pxProps = ['font-size', 'margin-top', 'margin-bottom', 'letter-spacing'];
                if (is_numeric($v) && in_array($prop, $pxProps))
                    $v .= 'px';
                if ($prop === 'letter-spacing' && ($v === 'tight' || $v === 'normal'))
                    $v = ($v === 'tight' ? '-0.025em' : '0px');
                return "$prop: $v";
            })->join('; ');
        @endphp
        <div id="mobile-hero-title"
            class="hero-content inline-block max-w-4xl mx-auto leading-tight drop-shadow-2xl hero-no-filter text-white text-5xl md:text-7xl whitespace-pre-wrap [hyphens:none] {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded' : '' }}"
            {!! $editable !!} @if($isEditor) contenteditable="true" @endif data-field="title_{{ app()->getLocale() }}"
            data-label="Hero Title" {!! $clickAction !!} @if($isEditor)
            :style="getFieldStyle({{ $index }}, '{{ 'title_' . app()->getLocale() }}')" @endif
            style="{!! $titleStyleStr !!}">
            {!! nl2br(e(app()->getLocale() == 'es' ? ($data['title_es'] ?? '') : ($data['title_en'] ?? ''))) !!}
        </div>

        <!-- Hero Subtitle -->
        @php
            $subField = 'subtitle_' . $locale;
            $otherSubField = 'subtitle_' . $otherLocale;

            $subStyles = [
                'color' => ($data[$subField . '_color'] ?? '') ?: ($data[$otherSubField . '_color'] ?? '') ?: 'inherit',
                'fontSize' => ($data[$subField . '_fontSize'] ?? '') ?: ($data[$otherSubField . '_fontSize'] ?? '') ?: 'inherit',
                'fontFamily' => ($data[$subField . '_fontFamily'] ?? '') ?: ($data[$otherSubField . '_fontFamily'] ?? '') ?: 'inherit',
                'fontWeight' => ($data[$subField . '_fontWeight'] ?? '') ?: ($data[$otherSubField . '_fontWeight'] ?? '') ?: 'normal',
                'textAlign' => ($data[$subField . '_textAlign'] ?? '') ?: ($data[$otherSubField . '_textAlign'] ?? '') ?: 'center',
                'letterSpacing' => ($data[$subField . '_letterSpacing'] ?? '') ?: ($data[$otherSubField . '_letterSpacing'] ?? '') ?: 'normal',
                'lineHeight' => ($data[$subField . '_lineHeight'] ?? '') ?: ($data[$otherSubField . '_lineHeight'] ?? '') ?: '1.2',
                'marginTop' => ($data[$subField . '_marginTop'] ?? '') ?: ($data[$otherSubField . '_marginTop'] ?? '') ?: '0px',
                'marginBottom' => ($data[$subField . '_marginBottom'] ?? '') ?: ($data[$otherSubField . '_marginBottom'] ?? '') ?: '0px',
                'transform' => "translate(" . (($data[$subField . '_translateX'] ?? '') ?: ($data[$otherSubField . '_translateX'] ?? '') ?: 0) . "px, " . (($data[$subField . '_translateY'] ?? '') ?: ($data[$otherSubField . '_translateY'] ?? '') ?: 0) . "px)"
            ];
            $subStyleStr = collect($subStyles)->map(function ($v, $k) {
                if ($k === 'transform')
                    return "transform: $v";
                $prop = str_replace(['fontSize', 'fontFamily', 'fontWeight', 'textAlign', 'letterSpacing', 'lineHeight', 'marginTop', 'marginBottom'], ['font-size', 'font-family', 'font-weight', 'text-align', 'letter-spacing', 'line-height', 'margin-top', 'margin-bottom'], $k);
                $pxProps = ['font-size', 'margin-top', 'margin-bottom'];
                if (is_numeric($v) && in_array($prop, $pxProps))
                    $v .= 'px';
                if ($prop === 'letter-spacing' && ($v === 'tight' || $v === 'normal'))
                    $v = ($v === 'tight' ? '-0.025em' : '0px');
                return "$prop: $v";
            })->join('; ');
        @endphp
        <div id="mobile-hero-subtitle"
            class="hero-content inline-block max-w-3xl mx-auto leading-relaxed drop-shadow-xl hero-no-filter text-white text-xl md:text-2xl whitespace-pre-wrap [hyphens:none] {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded' : '' }}"
            {!! $editable !!} @if($isEditor) contenteditable="true" @endif
            data-field="subtitle_{{ app()->getLocale() }}" data-label="Hero Subtitle" {!! $clickAction !!}
            @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ 'subtitle_' . app()->getLocale() }}')" @endif
            style="{!! $subStyleStr !!}">
            {!! nl2br(e(app()->getLocale() == 'es' ? ($data['subtitle_es'] ?? '') : ($data['subtitle_en'] ?? ''))) !!}
        </div>


    </div>

    <!-- Render Dynamic Elements (Free-form) -->
    @if($isEditor)
        <!-- Editor Mode: Alpine Rendering -->
        <template x-for="element in sections[{{ $index }}].data.elements" :key="element.id">
            <div class="draggable-element cursor-move absolute"
                :style="`left: ${element.x}%; top: ${element.y}%; width: ${element.width}px; height: ${element.height}px; z-index: ${(element.styles?.zIndex || 10)}`"
                :data-element-id="element.id" :data-x="0" :data-y="0" :data-type="element.type"
                :data-label="element.type === 'text' ? 'Text Block' : 'Floating Image'">

                <template x-if="element.type === 'text'">
                    <div class="w-full h-full" contenteditable="true"
                        style="outline: none; color: inherit; font-size: inherit;"
                        :style="{
                                                                                                                                                                                                                color: element.styles?.color || 'inherit',
                                                                                                                                                                                                                fontSize: element.styles?.fontSize || 'inherit',
                                                                                                                                                                                                                fontFamily: element.styles?.fontFamily || 'inherit',
                                                                                                                                                                                                                fontWeight: element.styles?.fontWeight || 'normal',
                                                                                                                                                                                                                letterSpacing: element.styles?.letterSpacing || 'normal',
                                                                                                                                                                                                                lineHeight: element.styles?.lineHeight || '1.2',
                                                                                                                                                                                                                marginTop: element.styles?.marginTop || '0px',
                                                                                                                                                                                                                marginBottom: element.styles?.marginBottom || '0px',
                                                                                                                                                                                                                textAlign: element.styles?.textAlign || 'left'
                                                                                                                                                                                                            }"
                        x-effect="if (document.activeElement !== $el && $el.innerText !== element.content) $el.innerText = element.content"
                        @input="element.content = $el.innerText">
                    </div>
                </template>

                <template x-if="element.type === 'image'">
                    <img :src="element.content" class="w-full h-full object-cover rounded shadow-lg pointer-events-none">
                </template>
            </div>
        </template>
    @else
        <!-- Public Mode: PHP Rendering -->
        @if(isset($data['elements']) && is_array($data['elements']))
            @foreach($data['elements'] as $el)
                @php
                    $style = "left: {$el['x']}%; top: {$el['y']}%; width: {$el['width']}px; height: {$el['height']}px; z-index: " . ($el['styles']['zIndex'] ?? 10) . ";";
                @endphp

                <div class="absolute" style="{{ $style }}">
                    @if($el['type'] === 'text')
                        @php
                            $elStyles = [
                                'color' => $el['styles']['color'] ?? 'inherit',
                                'fontSize' => $el['styles']['fontSize'] ?? 'inherit',
                                'fontFamily' => $el['styles']['fontFamily'] ?? 'inherit',
                                'fontWeight' => $el['styles']['fontWeight'] ?? 'normal',
                                'letterSpacing' => $el['styles']['letterSpacing'] ?? 'normal',
                                'lineHeight' => $el['styles']['lineHeight'] ?? '1.2',
                                'marginTop' => $el['styles']['marginTop'] ?? '0px',
                                'marginBottom' => $el['styles']['marginBottom'] ?? '0px',
                                'textAlign' => $el['styles']['textAlign'] ?? 'left'
                            ];
                            $elStyleStr = collect($elStyles)->map(fn($v, $k) => "$k: $v")->join('; ');
                        @endphp
                        <div class="w-full h-full" style="outline: none; {{ $elStyleStr }}">
                            {!! $el['content'] !!}
                        </div>
                    @elseif($el['type'] === 'image')
                        <img src="{{ $el['content'] }}" class="w-full h-full object-cover rounded shadow-lg pointer-events-none">
                    @endif
                </div>
            @endforeach
        @endif
    @endif

    <!-- Availability Bar -->
    <div id="booking-bar-wrapper"
        class="absolute top-0 left-0 right-0 z-[120] flex justify-center w-full pointer-events-none mt-2 md:mt-2 {{ $isEditor ? 'opacity-50 grayscale-[0.5]' : '' }}">
        <div id="booking-bar-container"
            class="w-full px-6 md:px-8 flex justify-center z-[120] transition-all duration-300 pointer-events-auto">

            <div id="booking-bar-inner"
                class="max-w-5xl shadow-2xl bg-white dark:bg-[#0b0c11] border-slate-100 dark:border-slate-800 w-full rounded-3xl border p-2 md:p-2 lg:p-4 grid grid-cols-1 md:grid-cols-4 gap-4 lg:gap-6 md:divide-x divide-slate-100 dark:divide-slate-800 transition-all duration-300">
                <div class="flex flex-col px-4 relative cursor-pointer"
                    onclick="document.getElementById('search-check-in').showPicker()">
                    <!-- Div invisible que captura todos los clics para evitar la selección de texto en Chrome -->
                    <div class="absolute inset-0 z-10"
                        onclick="document.getElementById('search-check-in').showPicker()"></div>
                    <label
                        class="text-[10px] uppercase tracking-widest font-black text-primary mb-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">calendar_month</span> Check-In
                    </label>
                    <input type="date" id="search-check-in"
                        class="bg-transparent border-none p-0 text-sm font-bold focus:ring-0 w-full cursor-pointer relative z-0"
                        style="color-scheme: dark;" value="{{ date('Y-m-d') }}">
                </div>
                <div class="flex flex-col px-4 relative cursor-pointer"
                    onclick="document.getElementById('search-check-out').showPicker()">
                    <!-- Div invisible que captura todos los clics para evitar la selección de texto en Chrome -->
                    <div class="absolute inset-0 z-10"
                        onclick="document.getElementById('search-check-out').showPicker()"></div>
                    <label
                        class="text-[10px] uppercase tracking-widest font-black text-primary mb-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">event_repeat</span> Check-Out
                    </label>
                    <input type="date" id="search-check-out"
                        class="bg-transparent border-none p-0 text-sm font-bold focus:ring-0 w-full cursor-pointer relative z-0"
                        style="color-scheme: dark;" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                <div class="flex flex-col px-4 relative" x-data="{ 
                                                                                                    open: false, 
                                                                                                    adults: 2, 
                                                                                                    children: 0, 
                                                                                                    rooms: 1,
                                                                                                    get displayString() {
                                                                                                        let text = this.adults + ' Adulto' + (this.adults > 1 ? 's' : '');
                                                                                                        if (this.children > 0) {
                                                                                                            text += ', ' + this.children + ' Niñ' + (this.children > 1 ? 'os' : 'o');
                                                                                                        }
                                                                                                        return text;
                                                                                                    }
                                                                                                }"
                    @click.away="open = false">
                    <label
                        class="text-[10px] uppercase tracking-widest font-black text-primary mb-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">group</span> {{ __('Huéspedes') }}
                    </label>

                    <button type="button" @click="open = !open"
                        class="w-full text-left bg-transparent border-none p-0 text-sm font-bold cursor-pointer flex justify-between items-center focus:outline-none">
                        <span x-text="displayString"></span>
                    </button>

                    <input type="hidden" id="search-guests" :value="adults + children">
                    <input type="hidden" id="search-rooms" :value="rooms">

                    <!-- Popover -->
                    <div x-show="open" x-transition.opacity.duration.200ms
                        class="absolute top-full left-0 mt-4 w-72 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-2 z-[70] text-slate-800 dark:text-slate-200"
                        style="display: none;">

                        <!-- Adultos -->
                        <div
                            class="flex items-center justify-between p-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <div class="text-sm font-bold">Adultos</div>
                                <div class="text-[10px] text-slate-400">Edad: &gt; 12 años</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="if(adults > 1) adults--"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition-colors focus:outline-none"><span
                                        class="material-symbols-outlined text-lg">remove</span></button>
                                <span class="w-4 text-center font-bold text-sm" x-text="adults"></span>
                                <button type="button" @click="adults++"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition-colors focus:outline-none"><span
                                        class="material-symbols-outlined text-lg">add</span></button>
                            </div>
                        </div>

                        <!-- Niños -->
                        <div
                            class="flex items-center justify-between p-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <div class="text-sm font-bold">Niños</div>
                                <div class="text-[10px] text-slate-400">Edad: 2 - 11 años</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="if(children > 0) children--"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition-colors focus:outline-none"><span
                                        class="material-symbols-outlined text-lg">remove</span></button>
                                <span class="w-4 text-center font-bold text-sm" x-text="children"></span>
                                <button type="button" @click="children++"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition-colors focus:outline-none"><span
                                        class="material-symbols-outlined text-lg">add</span></button>
                            </div>
                        </div>

                        <!-- Habitaciones -->
                        <div class="flex items-center justify-between p-3">
                            <div>
                                <div class="text-sm font-bold">Habitaciones</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="if(rooms > 1) rooms--"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition-colors focus:outline-none"><span
                                        class="material-symbols-outlined text-lg">remove</span></button>
                                <span class="w-4 text-center font-bold text-sm" x-text="rooms"></span>
                                <button type="button" @click="if(rooms < 10) rooms++"
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary hover:border-primary transition-colors focus:outline-none"><span
                                        class="material-symbols-outlined text-lg">add</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center pl-4">
                    <button onclick="processFastBooking()"
                        class="w-full h-full bg-primary text-white font-black rounded-xl hover:bg-primary/90 transition-all uppercase tracking-widest text-xs py-3 shadow-lg shadow-primary/20 pointer-events-auto">
                        {{ app()->getLocale() == 'es' ? 'Reservar Ahora' : 'Book Now' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* STICKY BOOKING BAR COMPACT MODE */
        #booking-bar-wrapper.is-sticky #booking-bar-container {
            top: 8px !important;
            z-index: 110 !important;
        }

        #booking-bar-wrapper.is-sticky #booking-bar-inner {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            min-height: 45px !important;
            align-items: center;
        }

        #booking-bar-wrapper.is-sticky .flex-col {
            flex-direction: row !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        #booking-bar-wrapper.is-sticky label {
            margin-bottom: 0 !important;
            white-space: nowrap;
            font-size: 9px !important;
        }

        #booking-bar-wrapper.is-sticky input[type="date"],
        #booking-bar-wrapper.is-sticky button[type="button"] {
            width: auto !important;
            font-size: 13px !important;
        }

        #booking-bar-wrapper.is-sticky button[onclick="processFastBooking()"] {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            font-size: 11px !important;
        }

        #booking-bar-wrapper.is-sticky .material-symbols-outlined {
            font-size: 14px !important;
        }

        @media (max-width: 767px) {
            #booking-bar-wrapper {
                display: none !important;
            }

            #booking-bar-wrapper.is-sticky .flex-col {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            #booking-bar-wrapper.is-sticky label {
                margin-bottom: 0.25rem !important;
            }

            #booking-bar-wrapper.is-sticky input[type="date"],
            #booking-bar-wrapper.is-sticky button[type="button"] {
                width: 100% !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('booking-bar-wrapper');
            const container = document.getElementById('booking-bar-container');
            const inner = document.getElementById('booking-bar-inner');
            let isSticky = false;

            if (!wrapper || !container || !inner) return;

            const checkSticky = () => {
                const rect = wrapper.getBoundingClientRect();
                // We check if the element has scrolled past distance 65px from top
                if (rect.top <= 35 && !isSticky) {
                    isSticky = true;
                    wrapper.classList.add('is-sticky');

                    container.classList.remove('px-6');
                    container.classList.add('px-4', 'fixed', 'left-0', 'right-0', 'py-0', 'mt-0', '!z-[110]');
                    container.style.top = '8px';

                    inner.classList.remove('max-w-5xl', 'shadow-2xl', 'bg-white', 'dark:bg-[#0b0c11]', 'border-slate-100', 'dark:border-slate-800');
                    inner.classList.add('max-w-[1400px]', 'shadow-lg', 'shadow-black/5', 'bg-white/95', 'backdrop-blur-xl', 'dark:bg-[#0b0c11]/95', 'border-slate-200', 'dark:border-slate-800');
                } else if (rect.top > 35 && isSticky) {
                    isSticky = false;
                    wrapper.classList.remove('is-sticky');

                    container.classList.remove('px-4', 'fixed', 'left-0', 'right-0', 'py-0', 'mt-0', '!z-[110]');
                    container.classList.add('px-6');
                    container.style.top = '';

                    inner.classList.remove('max-w-[1400px]', 'shadow-lg', 'shadow-black/5', 'bg-white/95', 'backdrop-blur-xl', 'dark:bg-[#0b0c11]/95', 'border-slate-200');
                    inner.classList.add('max-w-5xl', 'shadow-2xl', 'bg-white', 'dark:bg-[#0b0c11]', 'border-slate-100');
                }
            };

            window.addEventListener('scroll', checkSticky, { passive: true });
            window.addEventListener('resize', checkSticky, { passive: true });
            // Check on initial load
            checkSticky();
        });

        function processFastBooking() {
            const guestCount = parseInt(document.getElementById('search-guests')?.value || '2');
            let roomId, roomName;

            if (guestCount <= 2) {
                roomId = '2'; // Deluxe con cama extragrande
                roomName = '{{ app()->getLocale() == "es" ? "Habitación Deluxe con cama extragrande" : "Deluxe Room with Extra Large Bed" }}';
            } else {
                roomId = '1'; // Doble Deluxe
                roomName = '{{ app()->getLocale() == "es" ? "Habitación Doble Deluxe" : "Double Deluxe Room" }}';
            }

            // Llamar openBookingModal global, y luego aplicar manualmente el numero de habitaciones
            if (typeof openBookingModal === 'function') {
                openBookingModal(roomId, roomName, guestCount);

                // Aplicar cuartos
                setTimeout(() => {
                    const roomsSelect = document.querySelector('select[name="number_of_rooms"]');
                    const rooms = document.getElementById('search-rooms')?.value || 1;
                    if (roomsSelect && rooms) {
                        let val = parseInt(rooms);
                        roomsSelect.value = val >= 5 ? '5' : val.toString();
                    }

                    const checkIn = document.getElementById('search-check-in')?.value;
                    const checkOut = document.getElementById('search-check-out')?.value;
                    if (document.getElementById('modal-check-in') && checkIn) document.getElementById('modal-check-in').value = checkIn;
                    if (document.getElementById('modal-check-out') && checkOut) document.getElementById('modal-check-out').value = checkOut;
                }, 100);
            }
        }
    </script>
</div>