@props(['data', 'attractions' => [], 'mode' => 'public', 'index' => null])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';
@endphp

<section class="mb-12 scroll-mt-24 relative bg-cover bg-center pt-12" id="atractivos"
    style="background-image: url('{{ $data['bg_image'] ?? '' }}');">
    @if(isset($data['overlay_opacity']))
        <div class="absolute inset-0 bg-black" style="opacity: {{ $data['overlay_opacity'] }}%;"></div>
    @endif
    <div class="relative z-10 px-4 md:px-0">
        <div class="text-center mb-8">

            @php
                $locale = app()->getLocale();
                $otherLocale = $locale === 'es' ? 'en' : 'es';
                $titleField = 'attractions_title_' . $locale;
                $otherTitleField = 'attractions_title_' . $otherLocale;

                $titleStyles = [
                    'color' => ($data[$titleField . '_color'] ?? '') ?: ($data[$otherTitleField . '_color'] ?? '') ?: 'inherit',
                    'font-size' => ($data[$titleField . '_fontSize'] ?? '') ?: ($data[$otherTitleField . '_fontSize'] ?? '') ?: 'inherit',
                    'font-family' => ($data[$titleField . '_fontFamily'] ?? '') ?: ($data[$otherTitleField . '_fontFamily'] ?? '') ?: 'inherit',
                    'font-weight' => ($data[$titleField . '_fontWeight'] ?? '') ?: ($data[$otherTitleField . '_fontWeight'] ?? '') ?: '900',
                    'text-align' => ($data[$titleField . '_textAlign'] ?? '') ?: ($data[$otherTitleField . '_textAlign'] ?? '') ?: 'center',
                    'letter-spacing' => ($data[$titleField . '_letterSpacing'] ?? '') ?: ($data[$otherTitleField . '_letterSpacing'] ?? '') ?: '0px',
                    'line-height' => ($data[$titleField . '_lineHeight'] ?? '') ?: ($data[$otherTitleField . '_lineHeight'] ?? '') ?: '1.2',
                    'margin-top' => ($data[$titleField . '_marginTop'] ?? '') ?: ($data[$otherTitleField . '_marginTop'] ?? '') ?: '0px',
                    'margin-bottom' => ($data[$titleField . '_marginBottom'] ?? '') ?: ($data[$otherTitleField . '_marginBottom'] ?? '') ?: '0px',
                    'transform' => "translate(" . (($data[$titleField . '_translateX'] ?? '') ?: ($data[$otherTitleField . '_translateX'] ?? '') ?: 0) . "px, " . (($data[$titleField . '_translateY'] ?? '') ?: ($data[$otherTitleField . '_translateY'] ?? '') ?: 0) . "px)"
                ];
                $titleStyleStr = collect($titleStyles)->map(function ($v, $k) {
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
            <h2 class="text-4xl md:text-5xl font-black tracking-tight {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!}
                data-field="{{ $titleField }}" data-label="Attractions Title"
                @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ $titleField }}')" @endif style="{!! $titleStyleStr !!}">
                {{ app()->getLocale() == 'es' ? ($data['attractions_title_es'] ?? 'Local Attractions') : ($data['attractions_title_en'] ?? 'Local Attractions') }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if($isEditor)
                <!-- Alpine Loop for Editor -->
                <template x-for="attraction in attractions" :key="attraction.id">
                    <div
                        class="bg-white dark:bg-[#0b0c11] rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all group relative">

                        <!-- Delete Button -->
                        <button @click.stop="deleteAttraction(attraction.id)"
                            class="absolute top-4 right-4 z-20 bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-600">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>

                        <div class="h-64 overflow-hidden relative cursor-pointer"
                            @click.stop="openImageModal('attraction:' + attraction.id)">
                            <img :src="attraction.image_url"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <!-- Overlay for edit hint -->
                            <div
                                class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white font-bold flex items-center gap-2">
                                    <span class="material-symbols-outlined">edit</span> Cambiar Imagen
                                </span>
                            </div>
                        </div>
                        <div class="p-8">
                            <!-- Title (Editable) -->
                            <h4 class="text-2xl font-black mb-4 outline-none hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-text"
                                contenteditable="true" x-text="attraction.title_{{ app()->getLocale() }}"
                                :data-field="'attraction_' + attraction.id + '_title'" data-label="Título Atractivo"
                                :style="getFieldStyle({{ $index }}, 'attraction_' + attraction.id + '_title')"
                                @blur="updateAttraction(attraction.id, 'title_{{ app()->getLocale() }}', $el.innerText)">
                            </h4>
                            <!-- Description (Editable) -->
                            <div class="text-secondary dark:text-slate-400 text-sm leading-relaxed mb-6 outline-none hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-text"
                                contenteditable="true" x-html="attraction.description_{{ app()->getLocale() }}"
                                :data-field="'attraction_' + attraction.id + '_desc'"
                                data-label="Descripción Atractivo"
                                :style="getFieldStyle({{ $index }}, 'attraction_' + attraction.id + '_desc')"
                                @blur="updateAttraction(attraction.id, 'description_{{ app()->getLocale() }}', $el.innerText)">
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Add New Card -->
                <div @click="window.parent.postMessage({ type: 'DATA_ACTION', action: 'addAttraction', index: {{ $index }} }, '*')"
                    class="bg-slate-50 dark:bg-slate-800/50 rounded-[2rem] border-4 border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center min-h-[400px] cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                    <div
                        class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">add</span>
                    </div>
                    <h4 class="text-xl font-bold text-slate-500 dark:text-slate-400">Añadir Atractivo</h4>
                </div>

            @else
                <!-- Public Blade Loop -->
                @foreach($attractions as $attraction)
                    @php
                        $titleField = 'attraction_' . $attraction->id . '_title';
                        $descField = 'attraction_' . $attraction->id . '_desc';

                        $getStyles = function ($field) use ($data) {
                            $locale = app()->getLocale();
                            $otherLocale = $locale === 'es' ? 'en' : 'es';
                            $prefix = $field;

                            return [
                                'color' => ($data[$prefix . '_color'] ?? '') ?: 'inherit',
                                'font-size' => ($data[$prefix . '_fontSize'] ?? '') ?: 'inherit',
                                'font-family' => ($data[$prefix . '_fontFamily'] ?? '') ?: 'inherit',
                                'font-weight' => ($data[$prefix . '_fontWeight'] ?? '') ?: 'inherit',
                                'text-align' => ($data[$prefix . '_textAlign'] ?? '') ?: 'inherit',
                                'letter-spacing' => ($data[$prefix . '_letterSpacing'] ?? '') ?: 'inherit',
                                'line-height' => ($data[$prefix . '_lineHeight'] ?? '') ?: 'inherit',
                                'margin-top' => ($data[$prefix . '_marginTop'] ?? '') ?: '0px',
                                'margin-bottom' => ($data[$prefix . '_marginBottom'] ?? '') ?: '0px',
                                'transform' => "translate(" . ($data[$prefix . '_translateX'] ?? 0) . "px, " . ($data[$prefix . '_translateY'] ?? 0) . "px)"
                            ];
                        };

                        $renderStyles = function ($styles) {
                            return collect($styles)->filter(fn($v) => $v !== 'inherit' && $v !== '0px' && $v !== 'translate(0px, 0px)')->map(function ($v, $k) {
                                if ($k === 'transform')
                                    return "transform: $v";
                                $pxProps = ['font-size', 'margin-top', 'margin-bottom'];
                                if (is_numeric($v) && in_array($k, $pxProps))
                                    $v .= 'px';
                                if ($k === 'letter-spacing' && ($v === 'tight' || $v === 'normal'))
                                    $v = ($v === 'tight' ? '-0.025em' : '0px');
                                return "$k: $v";
                            })->join('; ');
                        };

                        $titleStyle = $renderStyles($getStyles($titleField));
                        $descStyle = $renderStyles($getStyles($descField));
                    @endphp
                    <div
                        class="bg-white dark:bg-[#0b0c11] rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all group">
                        <div class="h-64 overflow-hidden relative">
                            <img src="{{ $attraction->image_url }}"
                                width="600" height="400"
                                alt="{{ app()->getLocale() == 'es' ? $attraction->title_es : $attraction->title_en }}"
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                        <div class="p-8">
                            <h4 class="text-2xl font-black mb-4 whitespace-pre-wrap" style="{{ $titleStyle }}">
                                {!! nl2br(e(app()->getLocale() == 'es' ? $attraction->title_es : $attraction->title_en)) !!}
                            </h4>
                            <div class="text-secondary dark:text-slate-400 text-sm leading-relaxed mb-6 whitespace-pre-wrap"
                                style="{{ $descStyle }}">
                                {!! nl2br(e(app()->getLocale() == 'es' ? $attraction->description_es : $attraction->description_en)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Render Dynamic Elements (Free-form) -->
        <x-editor.dynamic-elements :data="$data" :mode="$mode" />

</section>