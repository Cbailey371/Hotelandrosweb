@props(['data', 'mode' => 'public', 'index' => null])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';
@endphp

<section class="mb-24 scroll-mt-24 relative" id="cafe-bar">
    <div class="flex flex-col lg:flex-row items-center gap-16">
        <div class="w-full lg:w-1/2">
            <div class="relative rounded-[3rem] overflow-hidden aspect-[4/3] shadow-2xl">
                <img src="{{ $data['bg_image'] ?? ($data['cafe_image'] ?? '/images/gallery/bar.png') }}"
                    width="800" height="600"
                    alt="{{ app()->getLocale() == 'es' ? ($data['cafe_title_es'] ?? 'Restaurante y Bar') : ($data['cafe_title_en'] ?? 'Restaurant and Bar') }}"
                    loading="lazy"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2s] hover:scale-105 {{ $isEditor ? 'hover:ring-2 ring-blue-500 cursor-pointer' : '' }}"
                    @if($isEditor) @click.stop="selectBackground()" @endif
                    data-field="bg_image" data-label="Cafe Image" data-type="image">
                <!-- Overlay -->
            </div>
        </div>
        <div class="w-full lg:w-1/2">
            @php
                $locale = app()->getLocale();
                $otherLocale = $locale === 'es' ? 'en' : 'es';
                $titleField = 'cafe_title_' . $locale;
                $otherTitleField = 'cafe_title_' . $otherLocale;

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
            <h2 id="mobile-cafe-title"
                class="text-4xl md:text-5xl font-black mb-8 tracking-tight leading-tight whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded draggable-offset' : '' }}"
                {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!}
                data-field="{{ 'cafe_title_' . app()->getLocale() }}" data-label="Cafe Title" @if($isEditor)
                @keydown.enter.prevent @endif
                @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ 'cafe_title_' . app()->getLocale() }}')" @endif
                style="{!! $titleStyleStr !!}">
                @if($isEditor)
                    {!! preg_replace('/style="([^"]*font-size:[^"]*)"/i', 'style=" "', app()->getLocale() == 'es' ? ($data['cafe_title_es'] ?? 'Sabores Artesanales & Coctelería') : ($data['cafe_title_en'] ?? 'Artisan Flavors & Cocktails')) !!}
                @else
                    {!! nl2br(e(app()->getLocale() == 'es' ? ($data['cafe_title_es'] ?? 'Sabores Artesanales & Coctelería') : ($data['cafe_title_en'] ?? 'Artisan Flavors & Cocktails'))) !!}
                @endif
            </h2>

            @php
                $descField = 'cafe_description_' . app()->getLocale();
                $otherDescField = app()->getLocale() == 'es' ? 'cafe_description_en' : 'cafe_description_es';
                $descStyles = [
                    'color' => ($data[$descField . '_color'] ?? '') ?: ($data[$otherDescField . '_color'] ?? '') ?: 'inherit',
                    'font-size' => ($data[$descField . '_fontSize'] ?? '') ?: ($data[$otherDescField . '_fontSize'] ?? '') ?: 'inherit',
                    'font-family' => ($data[$descField . '_fontFamily'] ?? '') ?: ($data[$otherDescField . '_fontFamily'] ?? '') ?: 'inherit',
                    'font-weight' => ($data[$descField . '_fontWeight'] ?? '') ?: ($data[$otherDescField . '_fontWeight'] ?? '') ?: 'normal',
                    'text-align' => ($data[$descField . '_textAlign'] ?? '') ?: ($data[$otherDescField . '_textAlign'] ?? '') ?: 'left',
                    'letter-spacing' => ($data[$descField . '_letterSpacing'] ?? '') ?: ($data[$otherDescField . '_letterSpacing'] ?? '') ?: 'normal',
                    'line-height' => ($data[$descField . '_lineHeight'] ?? '') ?: ($data[$otherDescField . '_lineHeight'] ?? '') ?: '1.625',
                    'margin-top' => ($data[$descField . '_marginTop'] ?? '') ?: ($data[$otherDescField . '_marginTop'] ?? '') ?: '0px',
                    'margin-bottom' => ($data[$descField . '_marginBottom'] ?? '') ?: ($data[$otherDescField . '_marginBottom'] ?? '') ?: '2.5rem',
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
            <div id="mobile-cafe-subtitle"
                class="text-lg text-secondary dark:text-slate-400 mb-10 leading-relaxed whitespace-pre-wrap {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded' : '' }}"
                {!! $editable !!} @if($isEditor) contenteditable="true" @endif {!! $clickAction !!}
                data-field="{{ $descField }}" data-label="Cafe Description" @if($isEditor) @keydown.enter.prevent @endif
                @if($isEditor) :style="getFieldStyle({{ $index }}, '{{ $descField }}')" @endif
                style="{!! $descStyleStr !!}">
                @if($isEditor)
                    {!! app()->getLocale() == 'es' ? ($data['cafe_description_es'] ?? 'Desde el espresso matutino...') : ($data['cafe_description_en'] ?? 'From morning espresso...') !!}
                @else
                    {!! nl2br(e(app()->getLocale() == 'es' ? ($data['cafe_description_es'] ?? 'Desde el espresso matutino...') : ($data['cafe_description_en'] ?? 'From morning espresso...'))) !!}
                @endif
            </div>

            <!-- Cafe Features Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mt-12">
                @if($isEditor)
                    <template x-for="feature in (sections[{{ $index }}].data.features || [])" :key="feature.id">
                        <div class="group relative flex flex-col items-center text-center p-4 rounded-3xl bg-slate-50 dark:bg-slate-800/30 transition-all hover:bg-white dark:hover:bg-slate-700 shadow-sm hover:shadow-xl draggable-offset"
                             :data-field="'cafe_feature_' + feature.id" data-label="Icono Café"
                             :style="getFieldStyle({{ $index }}, 'cafe_feature_' + feature.id)">
                            <!-- Delete Button -->
                            <button @click.stop="deleteCafeFeature({{ $index }}, feature.id)"
                                class="opacity-0 group-hover:opacity-100 absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs shadow-md transition-opacity z-[60]">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>

                            <!-- Animated Icon -->
                            <div class="w-12 h-12 mb-3 rounded-2xl bg-primary/10 flex flex-col items-center justify-center text-primary group-hover:scale-110 transition-transform animate-float cursor-pointer selection-none"
                                :data-field="'cafe_feature_' + feature.id" data-label="Icono Café"
                                title="Haz clic para seleccionar y cambiar icono en la barra lateral">
                                <span class="material-symbols-outlined text-3xl outline-none" x-text="feature.icon"></span>
                                <span class="text-[8px] opacity-40 uppercase font-bold tracking-widest mt-0.5">Edit</span>
                            </div>

                            <!-- Label -->
                            <span
                                class="text-sm font-bold text-slate-700 dark:text-slate-200 outline-none min-h-[1.5em] min-w-[20px] inline-block"
                                contenteditable="true" :data-field="'cafe_feature_' + feature.id" data-label="Icono Café"
                                @blur="updateCafeFeature({{ $index }}, feature.id, 'label_{{ app()->getLocale() }}', $el.innerText)"
                                x-text="feature['label_{{ app()->getLocale() }}'] || ''"></span>
                        </div>
                    </template>

                    <!-- Add Feature Button -->
                    <div @click="addCafeFeature({{ $index }})"
                        class="flex flex-col items-center justify-center p-4 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 group-hover:text-primary group-hover:scale-110 transition-all">
                            <span class="material-symbols-outlined">add</span>
                        </div>
                        <span class="text-xs font-bold text-slate-400 mt-2">Añadir Ícono</span>
                    </div>
                @else
                    @foreach(($data['features'] ?? []) as $feature)
                        @php
                            $label = app()->getLocale() == 'es' ? ($feature['label_es'] ?? '') : ($feature['label_en'] ?? '');
                            $featureField = 'cafe_feature_' . ($feature['id'] ?? 'default');
                            $fX = $data[$featureField . '_translateX'] ?? 0;
                            $fY = $data[$featureField . '_translateY'] ?? 0;
                            $fTransform = ($fX || $fY) ? "transform: translate({$fX}px, {$fY}px);" : "";
                        @endphp
                        <div class="flex flex-col items-center text-center group" style="{{ $fTransform }}">
                            <div
                                class="w-16 h-16 {{ $label ? 'mb-4' : '' }} rounded-3xl bg-primary/5 dark:bg-white/5 flex items-center justify-center text-primary transition-all duration-500 group-hover:bg-primary group-hover:text-white group-hover:rotate-6 group-hover:scale-110 shadow-sm border border-primary/10">
                                <span
                                    class="material-symbols-outlined text-4xl animate-float-slow">{{ $feature['icon'] ?? 'local_cafe' }}</span>
                            </div>
                            @if($label)
                                <span class="text-sm font-black tracking-tight text-slate-800 dark:text-slate-200">
                                    {{ $label }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-5px) rotate(2deg);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-float-slow {
            animation: float-slow 5s ease-in-out infinite;
        }
    </style>

    <!-- Render Dynamic Elements (Free-form) -->
    <x-editor.dynamic-elements :data="$data" :mode="$mode" />

</section>