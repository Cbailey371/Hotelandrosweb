@props(['data', 'carouselImages' => [], 'mode' => 'public', 'index' => null])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';
@endphp

@if(count($carouselImages) > 0 || $isEditor)
    <section class="mb-24 pb-0 scroll-mt-24 overflow-hidden px-4 md:px-0 relative bg-cover bg-center" id="galeria-destacada"
        style="background-image: url('{{ $data['bg_image'] ?? '' }}');">
        @if(isset($data['overlay_opacity']))
            <div class="absolute inset-0 bg-black" style="opacity: {{ $data['overlay_opacity'] }}%;"></div>
        @endif
        <div class="relative z-10 w-full">
            <div class="max-w-6xl mx-auto mb-12 text-center md:text-left">
                @php
                    $locale = app()->getLocale();
                    $otherLocale = $locale === 'es' ? 'en' : 'es';
                    $titleField = 'carousel_title_' . $locale;
                    $otherTitleField = 'carousel_title_' . $otherLocale;
                    $titleStyles = [
                        'color' => ($data[$titleField . '_color'] ?? '') ?: ($data[$otherTitleField . '_color'] ?? '') ?: 'inherit',
                        'font-size' => ($data[$titleField . '_fontSize'] ?? '') ?: ($data[$otherTitleField . '_fontSize'] ?? '') ?: 'inherit',
                        'font-family' => ($data[$titleField . '_fontFamily'] ?? '') ?: ($data[$otherTitleField . '_fontFamily'] ?? '') ?: 'inherit',
                        'font-weight' => ($data[$titleField . '_fontWeight'] ?? '') ?: ($data[$otherTitleField . '_fontWeight'] ?? '') ?: '900',
                        'text-align' => ($data[$titleField . '_textAlign'] ?? '') ?: ($data[$otherTitleField . '_textAlign'] ?? '') ?: 'center',
                        'letter-spacing' => ($data[$titleField . '_letterSpacing'] ?? '') ?: ($data[$otherTitleField . '_letterSpacing'] ?? '') ?: '-0.025em',
                        'line-height' => ($data[$titleField . '_lineHeight'] ?? '') ?: ($data[$otherTitleField . '_lineHeight'] ?? '') ?: '1.2',
                        'margin-top' => ($data[$titleField . '_marginTop'] ?? '') ?: ($data[$otherTitleField . '_marginTop'] ?? '') ?: '0px',
                        'margin-bottom' => ($data[$titleField . '_marginBottom'] ?? '') ?: ($data[$otherTitleField . '_marginBottom'] ?? '') ?: '0px',
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
                @endphp
                <h2 class="text-3xl md:text-5xl font-black mt-4 tracking-tight drop-shadow-sm text-slate-800 dark:text-white whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded' : '' }}"
                    {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!}
                    data-field="carousel_title_{{ app()->getLocale() }}" data-label="Gallery Title" @if($isEditor)
                    @keydown.enter.prevent @endif
                    @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ 'carousel_title_' . app()->getLocale() }}')" @endif
                    style="{!! $titleStyleStr !!}">
                    @if($isEditor)
                        {{ app()->getLocale() == 'es' ? ($data['carousel_title_es'] ?? 'Galería de Momentos') : ($data['carousel_title_en'] ?? 'Moments Gallery') }}
                    @else
                        {!! nl2br(e(app()->getLocale() == 'es' ? ($data['carousel_title_es'] ?? 'Galería de Momentos') : ($data['carousel_title_en'] ?? 'Moments Gallery'))) !!}
                    @endif
                </h2>
            </div>

            <div class="swiper home-carousel !overflow-visible relative" x-data>

                @if($isEditor)
                    <!-- EDITOR MODE: Horizontal Scroll & Management -->
                    <div class="flex overflow-x-auto gap-4 pb-4 snap-x snap-mandatory" style="scrollbar-width: thin;">

                        <!-- Loop through injected carouselImages -->
                        <template x-for="item in carouselImages" :key="item.id">
                            <div class="flex-shrink-0 w-[300px] md:w-[400px] snap-center relative group">
                                <div
                                    class="aspect-video rounded-[2rem] overflow-hidden shadow-md border-4 border-white dark:border-slate-800 relative">
                                    <img :src="item.image_url" class="w-full h-full object-cover">

                                    <!-- Delete Overlay -->
                                    <button @click.stop="deleteGalleryImage(item.id)"
                                        class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 z-10"
                                        title="Eliminar imagen">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Add New Card -->
                        <div class="flex-shrink-0 w-[200px] snap-center flex flex-col gap-2 items-center justify-center">

                            <button @click.stop="openImageModal('carousel')"
                                class="w-full aspect-[4/3] rounded-2xl border-4 border-dashed border-slate-300 dark:border-slate-600 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                                <div
                                    class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-xl">perm_media</span>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-slate-500 group-hover:text-purple-600 text-center px-2">Biblioteca</span>
                            </button>
                        </div>

                    </div>
                @else
                        <!-- PUBLIC MODE: Swiper Carousel -->
                        <style>
                            .home-carousel {
                                overflow: visible !important;
                            }

                            .home-carousel:not(.swiper-initialized) .swiper-wrapper {
                                display: flex !important;
                                justify-content: center !important;
                                flex-wrap: nowrap !important;
                            }
                        </style>

                        <!-- Separate Navigation Outer (Full Width) -->

                        <div class="swiper-wrapper">
                            @foreach($carouselImages as $item)
                                <div class="swiper-slide !w-[300px] md:!w-[500px]">
                                    <div
                                        class="relative aspect-video rounded-[3rem] overflow-hidden shadow-2xl shadow-primary/5 group cursor-grab active:cursor-grabbing border-4 border-white dark:border-slate-800">
                                        <img src="{{ $item->image_url }}"
                                            width="800" height="450"
                                            alt="{{ app()->getLocale() == 'es' ? ($item->title_es ?? 'Imagen de galería') : ($item->title_en ?? 'Gallery image') }}"
                                            loading="lazy"
                                            class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-110">
                                    </div>
                                </div>
                            @endforeach
                        </div>


                    </div>
                @endif
        </div>

        <!-- Render Dynamic Elements (Free-form) -->
        <x-editor.dynamic-elements :data="$data" :mode="$mode" />

    </section>
@else
    @if($isEditor)
        <div class="p-10 text-center text-slate-500 bg-slate-100 rounded-xl m-10">
            No carousel images found. Add images marked as 'carousel' in the gallery to see them here.
        </div>
    @endif
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize ALL Home Carousels (Robustness for multiple instances)
            const carousels = document.querySelectorAll('.home-carousel');
            carousels.forEach(carousel => {
                if (carousel.swiper) return; // Already initialized

                try {
                    new Swiper(carousel, {
                        slidesPerView: 'auto',
                        centeredSlides: true,
                        spaceBetween: 20,
                        loop: true,
                        autoHeight: true,
                        grabCursor: true,
                        observer: true,
                        observeParents: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
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
                } catch (e) {
                    console.error('Swiper Init Error:', e);
                }
            });
        });
    </script>
@endpush