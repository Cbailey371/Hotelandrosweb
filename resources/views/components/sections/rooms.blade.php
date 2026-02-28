@props(['data', 'rooms', 'mode' => 'public', 'index' => null])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';
@endphp

@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'es' ? 'en' : 'es';

    // Title Styles
    $titleField = 'rooms_title_' . $locale;
    $otherTitleField = 'rooms_title_' . $otherLocale;
    $titleStyles = [
        'color' => ($data[$titleField . '_color'] ?? '') ?: ($data[$otherTitleField . '_color'] ?? '') ?: 'inherit',
        'font-size' => ($data[$titleField . '_fontSize'] ?? '') ?: ($data[$otherTitleField . '_fontSize'] ?? '') ?: 'inherit',
        'font-family' => ($data[$titleField . '_fontFamily'] ?? '') ?: ($data[$otherTitleField . '_fontFamily'] ?? '') ?: 'inherit',
        'font-weight' => ($data[$titleField . '_fontWeight'] ?? '') ?: ($data[$otherTitleField . '_fontWeight'] ?? '') ?: '900',
        'text-align' => ($data[$titleField . '_textAlign'] ?? '') ?: ($data[$otherTitleField . '_textAlign'] ?? '') ?: 'center',
        'letter-spacing' => ($data[$titleField . '_letterSpacing'] ?? '') ?: ($data[$otherTitleField . '_letterSpacing'] ?? '') ?: 'tight',
        'line-height' => ($data[$titleField . '_lineHeight'] ?? '') ?: ($data[$otherTitleField . '_lineHeight'] ?? '') ?: '1.2',
        'margin-top' => ($data[$titleField . '_marginTop'] ?? '') ?: ($data[$otherTitleField . '_marginTop'] ?? '') ?: '0px',
        'margin-bottom' => ($data[$titleField . '_marginBottom'] ?? '') ?: ($data[$otherTitleField . '_marginBottom'] ?? '') ?: '2rem',
        'transform' => "translate(" . (($data[$titleField . '_translateX'] ?? '') ?: ($data[$otherTitleField . '_translateX'] ?? '') ?: 0) . "px, " . (($data[$titleField . '_translateY'] ?? '') ?: ($data[$otherTitleField . '_translateY'] ?? '') ?: 0) . "px)"
    ];
    $titleStyleStr = collect($titleStyles)->map(function ($v, $k) {
        if ($k === 'transform')
            return "transform: $v";
        $pxProps = ['font-size', 'margin-top', 'margin-bottom'];
        if (is_numeric($v) && in_array($k, $pxProps))
            $v .= 'px';
        if ($k === 'letter-spacing' && ($v === 'tight' || $v === 'normal'))
            $v = ($v === 'tight' ? '-0.025em' : '0px');
        return "$k: $v";
    })->join('; ');

    // Description Styles
    $descField = 'rooms_description_' . $locale;
    $otherDescField = 'rooms_description_' . $otherLocale;
    $descStyles = [
        'color' => ($data[$descField . '_color'] ?? '') ?: ($data[$otherDescField . '_color'] ?? '') ?: 'inherit',
        'font-size' => ($data[$descField . '_fontSize'] ?? '') ?: ($data[$otherDescField . '_fontSize'] ?? '') ?: 'inherit',
        'font-family' => ($data[$descField . '_fontFamily'] ?? '') ?: ($data[$otherDescField . '_fontFamily'] ?? '') ?: 'inherit',
        'font-weight' => ($data[$descField . '_fontWeight'] ?? '') ?: ($data[$otherDescField . '_fontWeight'] ?? '') ?: 'normal',
        'text-align' => ($data[$descField . '_textAlign'] ?? '') ?: ($data[$otherDescField . '_textAlign'] ?? '') ?: 'center',
        'letter-spacing' => ($data[$descField . '_letterSpacing'] ?? '') ?: ($data[$otherDescField . '_letterSpacing'] ?? '') ?: 'normal',
        'line-height' => ($data[$descField . '_lineHeight'] ?? '') ?: ($data[$otherDescField . '_lineHeight'] ?? '') ?: '1.625',
        'margin-top' => ($data[$descField . '_marginTop'] ?? '') ?: ($data[$otherDescField . '_marginTop'] ?? '') ?: '0px',
        'margin-bottom' => ($data[$descField . '_marginBottom'] ?? '') ?: ($data[$otherDescField . '_marginBottom'] ?? '') ?: '0px',
        'transform' => "translate(" . (($data[$descField . '_translateX'] ?? '') ?: ($data[$otherDescField . '_translateX'] ?? '') ?: 0) . "px, " . (($data[$descField . '_translateY'] ?? '') ?: ($data[$otherDescField . '_translateY'] ?? '') ?: 0) . "px)"
    ];
    $descStyleStr = collect($descStyles)->map(function ($v, $k) {
        if ($k === 'transform')
            return "transform: $v";
        $pxProps = ['font-size', 'margin-top', 'margin-bottom'];
        if (is_numeric($v) && in_array($k, $pxProps))
            $v .= 'px';
        if ($k === 'letter-spacing' && ($v === 'tight' || $v === 'normal'))
            $v = ($v === 'tight' ? '-0.025em' : '0px');
        return "$k: $v";
    })->join('; ');
@endphp

<div class="max-w-[1440px] mx-auto px-4 md:px-8 relative">
    <!-- Habitaciones Section -->
    <section id="habitaciones" class="scroll-mt-32 mb-24">
        <div class="flex flex-col items-center text-center mb-16">

            <h2 class="text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-800 dark:text-white whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 cursor-pointer rounded' : '' }}"
                {!! $editable !!} {!! $clickAction !!}
                @if($isEditor)
                    :style="getFieldStyle({{ $index }}, '{{ 'rooms_title_' . app()->getLocale() }}')" data-type="text"
                    data-field="{{ 'rooms_title_' . app()->getLocale() }}" data-label="Rooms Title" @keydown.enter.prevent
                @endif style="{!! $titleStyleStr !!}">
                @if($isEditor)
                    {{ app()->getLocale() == 'es' ? ($data['rooms_title_es'] ?? ($isEditor ? 'Título de la Sección' : '')) : ($data['rooms_title_en'] ?? ($isEditor ? 'Section Title' : '')) }}
                @else
                    {!! nl2br(e(app()->getLocale() == 'es' ? ($data['rooms_title_es'] ?? '') : ($data['rooms_title_en'] ?? ''))) !!}
                @endif
            </h2>
            <div class="text-secondary max-w-2xl text-lg mx-auto whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 cursor-pointer rounded' : '' }}"
                {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!} data-type="text"
                data-field="{{ 'rooms_description_' . app()->getLocale() }}" data-label="Rooms Description"
                @if($isEditor) @keydown.enter.prevent @endif
                @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ 'rooms_description_' . app()->getLocale() }}')" @endif
                style="{!! $descStyleStr !!}">
                @if($isEditor)
                    {!! app()->getLocale() == 'es' ? ($data['rooms_description_es'] ?? ($isEditor ? 'Descripción breve de esta sección...' : '')) : ($data['rooms_description_en'] ?? ($isEditor ? 'Short description of this section...' : '')) !!}
                @else
                    {!! nl2br(e(app()->getLocale() == 'es' ? ($data['rooms_description_es'] ?? '') : ($data['rooms_description_en'] ?? ''))) !!}
                @endif
            </div>
            <div class="flex flex-col gap-12 w-full max-w-6xl mx-auto">
                @if(isset($rooms))
                    @foreach($rooms as $room)
                        <div x-data
                            class="w-full bg-white dark:bg-[#0b0c11] rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 group flex flex-col md:flex-row transition-all hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-1">
                            <!-- Image & Gallery Section -->
                            <div
                                class="w-full md:w-[40%] flex flex-col items-center justify-center p-6 md:p-8 bg-slate-50 dark:bg-slate-900/50">
                                <div
                                    class="w-full h-48 md:h-64 overflow-hidden relative rounded-2xl shadow-md mb-4 group/image">
                                    <img src="{{ $room->image }}"
                                        width="600" height="400"
                                        alt="{{ app()->getLocale() == 'es' ? $room->name_es : $room->name_en }}"
                                        loading="lazy"
                                        class="h-full w-full object-cover transition-transform duration-[2s] hover:scale-105">
                                </div>

                                @if(isset($room->galleries) && $room->galleries->count() > 0)
                                    <div x-data="{ 
                                                                                                                                                    roomGalleries: {{ Js::from($room->galleries->pluck('image_url')) }},
                                                                                                                                                    roomName: '{{ app()->getLocale() == 'es' ? addslashes($room->name_es) : addslashes($room->name_en) }}'
                                                                                                                                                }"
                                        class="w-full md:w-auto">
                                        <button @click="$dispatch('open-room-gallery', { images: roomGalleries, title: roomName })"
                                            class="w-full px-6 py-2.5 bg-primary text-white text-xs font-black rounded-xl hover:bg-primary/90 transition-all uppercase tracking-widest shadow-md flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">photo_library</span>
                                            {{ __('Ver Galería') }}
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Content Section -->
                            <div
                                class="w-full md:w-[40%] p-8 md:p-10 flex flex-col justify-center border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-800">
                                <h4 class="text-2xl md:text-3xl font-black mb-3 text-slate-800 dark:text-white leading-tight">
                                    {{ app()->getLocale() == 'es' ? $room->name_es : $room->name_en }}
                                </h4>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">
                                    {{ app()->getLocale() == 'es' ? $room->description_es : $room->description_en }}
                                </p>

                                <div class="flex flex-wrap gap-2 mb-6">
                                    @php
                                        $amenityMetadata = [
                                            'wifi' => ['icon' => 'wifi', 'label' => __('WiFi')],
                                            'ac' => ['icon' => 'ac_unit', 'label' => __('Aire Acondicionado')],
                                            'tv' => ['icon' => 'tv', 'label' => __('TV')],
                                            'safe' => ['icon' => 'lock', 'label' => __('Caja Fuerte')],
                                            'shower' => ['icon' => 'shower', 'label' => __('Ducha')],
                                            'coffee' => ['icon' => 'coffee_maker', 'label' => __('Cafetera')],
                                            'minibar' => ['icon' => 'kitchen', 'label' => __('Minibar')],
                                            'balcony' => ['icon' => 'deck', 'label' => __('Balcón')],
                                            'king' => ['icon' => 'king_bed', 'label' => __('Cama King')],
                                            'bathtub' => ['icon' => 'bathtub', 'label' => __('Bañera')],
                                            'jacuzzi' => ['icon' => 'hot_tub', 'label' => __('Jacuzzi')],
                                            'double_bed' => ['icon' => 'bed', 'label' => __('Cama Doble')],
                                        ];
                                    @endphp
                                    @foreach($room->amenities ?? [] as $amenityKey)
                                        @if(isset($amenityMetadata[$amenityKey]))
                                            <div
                                                class="flex items-center gap-1.5 px-3 py-1 bg-slate-50 dark:bg-slate-900 rounded-xl text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider border border-slate-100 dark:border-slate-800">
                                                <span class="material-symbols-outlined text-sm text-primary font-bold">
                                                    {{ $amenityMetadata[$amenityKey]['icon'] }}
                                                </span>
                                                {{ $amenityMetadata[$amenityKey]['label'] }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary text-xl">group</span>
                                    <span class="text-sm font-black text-slate-700 dark:text-white">
                                        {{ $room->capacity }} {{ __('Pers.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Price/Action Section -->
                            <div class="w-full md:w-[20%] p-8 md:p-10 flex flex-col items-center justify-center">
                                <div class="text-center mb-6">
                                    <span
                                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">{{ __('Desde') }}</span>
                                    <span class="text-4xl font-black text-primary">${{ number_format($room->price, 0) }}</span>
                                </div>

                                <button
                                    onclick="openBookingModal('{{ $room->id }}', '{{ app()->getLocale() == 'es' ? addslashes($room->name_es) : addslashes($room->name_en) }}', {{ $room->capacity ?? 'null' }})"
                                    class="w-full py-4 bg-primary text-white text-xs font-black rounded-2xl hover:bg-primary/90 transition-all uppercase tracking-widest shadow-lg shadow-primary/20">
                                    {{ app()->getLocale() == 'es' ? 'Reservar' : 'Book Now' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center p-10 bg-slate-50 w-full rounded-2xl">
                        <p class="text-slate-500 font-bold">No rooms data available via Editor yet.</p>
                    </div>
                @endif
            </div>
            <!-- Render Dynamic Elements (Free-form) -->
            <x-editor.dynamic-elements :data="$data" :mode="$mode" />
    </section>

    <!-- Room Gallery Lightbox -->
    <div x-data="{ 
            isOpen: false, 
            images: [], 
            title: '',
            currentIndex: 0,
            openGallery(event) {
                this.images = event.detail.images;
                this.title = event.detail.title;
                this.currentIndex = 0;
                if(this.images.length > 0) {
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                }
            },
            closeGallery() {
                this.isOpen = false;
                document.body.style.overflow = '';
            },
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            },
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        }" @open-room-gallery.window="openGallery($event)" @keydown.escape.window="closeGallery()"
        @keydown.right.window="if(isOpen) next()" @keydown.left.window="if(isOpen) prev()">

        <div x-show="isOpen" x-transition.opacity.duration.300ms
            class="fixed inset-0 z-[100] bg-black/95 flex flex-col items-center justify-center" x-cloak
            style="display: none;">

            <!-- Toolbar -->
            <div
                class="absolute top-0 left-0 right-0 p-4 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent z-10">
                <h3 class="text-white text-xl font-bold px-4 drop-shadow-md" x-text="title"></h3>
                <button @click="closeGallery()"
                    class="text-white/80 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors outline-none focus:outline-none">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
            </div>

            <!-- Main Image Area -->
            <div class="relative w-full h-full flex items-center justify-center p-4 md:p-12">
                <!-- Navigation Prev -->
                <button @click="prev()" x-show="images.length > 1"
                    class="absolute left-2 md:left-8 text-white/50 hover:text-white p-2 md:p-4 rounded-full hover:bg-white/10 transition-colors z-10 outline-none focus:outline-none bg-black/20 hover:bg-black/40 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-4xl md:text-5xl">chevron_left</span>
                </button>

                <!-- Image Display -->
                <img :src="images[currentIndex]"
                    class="max-w-full max-h-full object-contain shadow-2xl rounded-lg select-none" alt="Room Image"
                    x-transition.opacity.duration.200ms>

                <!-- Navigation Next -->
                <button @click="next()" x-show="images.length > 1"
                    class="absolute right-2 md:right-8 text-white/50 hover:text-white p-2 md:p-4 rounded-full hover:bg-white/10 transition-colors z-10 outline-none focus:outline-none bg-black/20 hover:bg-black/40 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-4xl md:text-5xl">chevron_right</span>
                </button>
            </div>

            <!-- Counter -->
            <div
                class="absolute bottom-0 left-0 right-0 p-6 flex justify-center bg-gradient-to-t from-black/80 to-transparent pointer-events-none">
                <div
                    class="text-white font-bold text-sm px-6 py-2 bg-black/50 rounded-full backdrop-blur-md border border-white/10 shadow-lg tracking-widest">
                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </div>
            </div>
        </div>
    </div>
</div>