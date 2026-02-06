@props(['data', 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = $isEditor ? '@click.stop="$store.editor.selectElement($el)"' : '';
@endphp

<section class="mb-24 scroll-mt-24" id="contacto">
    <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
        <div class="w-full lg:w-1/2">
            <!-- Map Container -->
            <div
                class="relative rounded-[3rem] overflow-hidden aspect-video shadow-2xl border-8 border-white dark:border-slate-800">
                <iframe src="{{ $data['google_maps_iframe'] ?? 'https://www.google.com/maps/embed?pb=...' }}"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    class="grayscale hover:grayscale-0 transition-all duration-700 pointer-events-none"></iframe>
            </div>
        </div>
        <div class="w-full lg:w-1/2">
            <span
                class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest mb-4">
                {{ app()->getLocale() == 'es' ? ($data['location_badge_es'] ?? 'Donde estamos ubicados') : ($data['location_badge_en'] ?? 'Location') }}
            </span>
            <h2 class="text-4xl md:text-5xl font-black mb-8 tracking-tight leading-tight {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!} {!! $clickAction !!} data-field="location_title_{{ app()->getLocale() }}"
                data-label="Location Title">
                {!! app()->getLocale() == 'es' ? ($data['location_title_es'] ?? 'Explore the Gateway') : ($data['location_title_en'] ?? 'Explore the Gateway') !!}
            </h2>
            <div class="text-lg text-secondary dark:text-slate-400 mb-10 leading-relaxed {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!} {!! $clickAction !!} data-field="location_description_{{ app()->getLocale() }}"
                data-label="Location Description">
                {!! app()->getLocale() == 'es' ? ($data['location_description_es'] ?? 'Ubicado en...') : ($data['location_description_en'] ?? 'Located on...') !!}
            </div>
        </div>
    </div>
</section>