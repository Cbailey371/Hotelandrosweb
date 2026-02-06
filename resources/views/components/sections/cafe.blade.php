@props(['data', 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = $isEditor ? '@click.stop="$store.editor.selectElement($el)"' : '';
@endphp

<section class="mb-24 scroll-mt-24" id="cafe-bar">
    <div class="flex flex-col lg:flex-row items-center gap-16">
        <div class="w-full lg:w-1/2">
            <div class="relative rounded-[3rem] overflow-hidden aspect-[4/3] shadow-2xl">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] hover:scale-105 {{ $isEditor ? 'hover:ring-2 ring-blue-500 cursor-pointer' : '' }}"
                    {!! $clickAction !!} data-field="cafe_image" data-label="Cafe Image" data-type="image"
                    style='background-image: url("{{ $data['cafe_image'] ?? '/images/gallery/bar.png' }}");'
                    loading="lazy">
                </div>
                <!-- Overlay -->
                <!-- ... overlay logic if needed, simplify for editor visibility ... -->
            </div>
        </div>
        <div class="w-full lg:w-1/2">
            <div
                class="inline-block px-4 py-1.5 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-full mb-6 uppercase tracking-widest">
                ANDROS CAFE
            </div>

            <h2 class="text-4xl md:text-5xl font-black mb-8 tracking-tight leading-tight {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!} {!! $clickAction !!} data-field="cafe_title_{{ app()->getLocale() }}"
                data-label="Cafe Title">
                {{ app()->getLocale() == 'es' ? ($data['cafe_title_es'] ?? 'Sabores Artesanales & Coctelería') : ($data['cafe_title_en'] ?? 'Artisan Flavors & Cocktails') }}
            </h2>

            <div class="ql-editor text-lg text-secondary dark:text-slate-400 mb-10 leading-relaxed {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!} {!! $clickAction !!} data-field="cafe_description_{{ app()->getLocale() }}"
                data-label="Cafe Description">
                {!! app()->getLocale() == 'es' ? ($data['cafe_description_es'] ?? 'Desde el espresso matutino...') : ($data['cafe_description_en'] ?? 'From morning espresso...') !!}
            </div>
        </div>
    </div>
</section>