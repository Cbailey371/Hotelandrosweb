@props(['data', 'mode' => 'public', 'index' => null])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';
@endphp

<section class="mb-24 scroll-mt-24 relative bg-cover bg-center" id="contacto"
    style="background-image: url('{{ $data['bg_image'] ?? '' }}');">
    @if(isset($data['overlay_opacity']))
        <div class="absolute inset-0 bg-black" style="opacity: {{ $data['overlay_opacity'] }}%;"></div>
    @endif
    <div class="relative z-10 p-8">
        <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
            <div class="w-full lg:w-1/2">
                <!-- Map Container -->
                <div
                    class="relative rounded-[3rem] overflow-hidden aspect-video shadow-2xl border-8 border-white dark:border-slate-800">
                    <iframe
                        src="{{ $data['google_maps_iframe'] ?? 'https://maps.google.com/maps?q=Hotel%20Andros,%20Col%C3%B3n,%20Panam%C3%A1&t=&z=18&ie=UTF8&iwloc=&output=embed' }}"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        class="transition-all duration-700"></iframe>
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                @php
                    $locale = app()->getLocale();
                    $otherLocale = $locale === 'es' ? 'en' : 'es';
                    $titleField = 'location_title_' . $locale;
                    $otherTitleField = 'location_title_' . $otherLocale;

                    $titleStyles = [
                        'color' => ($data[$titleField . '_color'] ?? '') ?: ($data[$otherTitleField . '_color'] ?? '') ?: 'inherit',
                        'font-size' => ($data[$titleField . '_fontSize'] ?? '') ?: ($data[$otherTitleField . '_fontSize'] ?? '') ?: 'inherit',
                        'font-family' => ($data[$titleField . '_fontFamily'] ?? '') ?: ($data[$otherTitleField . '_fontFamily'] ?? '') ?: 'inherit',
                        'font-weight' => ($data[$titleField . '_fontWeight'] ?? '') ?: ($data[$otherTitleField . '_fontWeight'] ?? '') ?: '900',
                        'text-align' => ($data[$titleField . '_textAlign'] ?? '') ?: ($data[$otherTitleField . '_textAlign'] ?? '') ?: 'left',
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
                @endphp
                <h2 id="mobile-contact-title"
                    class="text-4xl md:text-5xl font-black mb-8 tracking-tight leading-tight whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded' : '' }}"
                    {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!}
                    data-field="{{ $titleField }}" data-label="Location Title" @if($isEditor) @keydown.enter.prevent
                    @endif
                @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ $titleField }}')" @endif
                    style="{!! $titleStyleStr !!}">
                    @if($isEditor)
                        {!! preg_replace('/style="([^"]*font-size:[^"]*)"/i', 'style=" "', app()->getLocale() == 'es' ? ($data['location_title_es'] ?? 'Ubicación') : ($data['location_title_en'] ?? 'Location')) !!}
                    @else
                        {!! nl2br(e(app()->getLocale() == 'es' ? ($data['location_title_es'] ?? 'Ubicación') : ($data['location_title_en'] ?? 'Location'))) !!}
                    @endif
                </h2>

                @php
                    $descField = 'location_description_' . app()->getLocale();
                    $otherDescField = app()->getLocale() == 'es' ? 'location_description_en' : 'location_description_es';
                    $descStyles = [
                        'color' => ($data[$descField . '_color'] ?? '') ?: ($data[$otherDescField . '_color'] ?? '') ?: 'inherit',
                        'font-size' => ($data[$descField . '_fontSize'] ?? '') ?: ($data[$otherDescField . '_fontSize'] ?? '') ?: 'inherit',
                        'font-family' => ($data[$descField . '_fontFamily'] ?? '') ?: ($data[$otherDescField . '_fontFamily'] ?? '') ?: 'inherit',
                        'font-weight' => ($data[$descField . '_fontWeight'] ?? '') ?: ($data[$otherDescField . '_fontWeight'] ?? '') ?: 'normal',
                        'text-align' => ($data[$descField . '_textAlign'] ?? '') ?: ($data[$otherDescField . '_textAlign'] ?? '') ?: 'left',
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
                <div id="mobile-contact-subtitle"
                    class="text-lg text-secondary dark:text-slate-400 mb-10 leading-relaxed whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded' : '' }}"
                    {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!}
                    data-field="{{ $descField }}" data-label="Location Description" @if($isEditor)
                    @keydown.enter.prevent @endif @if($isEditor)
                    :style="getFieldStyle({{ $index }}, '{{ $descField }}')" @endif style="{{ $descStyleStr }}">
                    @if($isEditor)
                        {!! app()->getLocale() == 'es' ? ($data['location_description_es'] ?? 'Ubicado en...') : ($data['location_description_en'] ?? 'Located on...') !!}
                    @else
                        {!! nl2br(e(app()->getLocale() == 'es' ? ($data['location_description_es'] ?? 'Ubicado en...') : ($data['location_description_en'] ?? 'Located on...'))) !!}
                    @endif
                </div>
            </div>
        </div>

        <!-- Render Dynamic Elements (Free-form) -->
        <x-editor.dynamic-elements :data="$data" :mode="$mode" />

</section>