@props(['data', 'carouselImages' => [], 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = $isEditor ? '@click.stop="$store.editor.selectElement($el)"' : '';
@endphp

@if(count($carouselImages) > 0 || $isEditor)
    <section class="mb-0 scroll-mt-24 overflow-hidden px-4 md:px-0" id="galeria-destacada">
        <div class="max-w-6xl mx-auto mb-10 text-center md:text-left">
            <span
                class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-[0.2em] mb-4">
                {{ app()->getLocale() == 'es' ? ($data['carousel_badge_es'] ?? 'Visual Experience') : ($data['carousel_badge_en'] ?? 'Visual Experience') }}
            </span>
            <h2 class="text-3xl md:text-5xl font-black mt-4 tracking-tight drop-shadow-sm text-slate-800 dark:text-white {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!} {!! $clickAction !!} data-field="carousel_title_{{ app()->getLocale() }}"
                data-label="Gallery Title">
                {{ app()->getLocale() == 'es' ? ($data['carousel_title_es'] ?? 'Galería de Momentos') : ($data['carousel_title_en'] ?? 'Moments Gallery') }}
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
@else
    @if($isEditor)
        <div class="p-10 text-center text-slate-500 bg-slate-100 rounded-xl m-10">
            No carousel images found. Add images marked as 'carousel' in the gallery to see them here.
        </div>
    @endif
@endif