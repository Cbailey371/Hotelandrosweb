@props(['data', 'attractions' => [], 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = $isEditor ? '@click.stop="$store.editor.selectElement($el)"' : '';
@endphp

<section class="mb-24 scroll-mt-24" id="atractivos">
    <div class="text-center mb-16">
        <span
            class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest mb-4">
            {{ app()->getLocale() == 'es' ? ($data['attractions_badge_es'] ?? 'EXPLORE PANAMA') : ($data['attractions_badge_en'] ?? 'EXPLORE PANAMA') }}
        </span>
        <h2 class="text-4xl md:text-5xl font-black tracking-tight {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
            {!! $editable !!} {!! $clickAction !!} data-field="attractions_title_{{ app()->getLocale() }}"
            data-label="Attractions Title">
            {{ app()->getLocale() == 'es' ? ($data['attractions_title_es'] ?? 'Local Attractions') : ($data['attractions_title_en'] ?? 'Local Attractions') }}
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