@props(['data', 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = '';
@endphp

@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'es' ? 'en' : 'es';

    // Description Styles
    $descField = 'footer_description_' . $locale;
    $otherDescField = 'footer_description_' . $otherLocale;
    $descStyles = [
        'font-size' => ($data[$descField . '_fontSize'] ?? '') ?: ($data[$otherDescField . '_fontSize'] ?? '') ?: 'inherit',
        'font-family' => ($data[$descField . '_fontFamily'] ?? '') ?: ($data[$otherDescField . '_fontFamily'] ?? '') ?: 'inherit',
        'font-weight' => ($data[$descField . '_fontWeight'] ?? '') ?: ($data[$otherDescField . '_fontWeight'] ?? '') ?: 'bold',
        'text-align' => ($data[$descField . '_textAlign'] ?? '') ?: ($data[$otherDescField . '_textAlign'] ?? '') ?: 'left',
        'letter-spacing' => ($data[$descField . '_letterSpacing'] ?? '') ?: ($data[$otherDescField . '_letterSpacing'] ?? '') ?: 'normal',
        'line-height' => ($data[$descField . '_lineHeight'] ?? '') ?: ($data[$otherDescField . '_lineHeight'] ?? '') ?: '1.625',
    ];
    $descStyleStr = collect($descStyles)->map(fn($v, $k) => "$k: $v")->join('; ');

    // Contact Description Styles
    $contactField = 'footer_contact_description_' . $locale;
    $otherContactField = 'footer_contact_description_' . $otherLocale;
    $contactStyles = [
        'font-size' => ($data[$contactField . '_fontSize'] ?? '') ?: ($data[$otherContactField . '_fontSize'] ?? '') ?: 'inherit',
        'font-family' => ($data[$contactField . '_fontFamily'] ?? '') ?: ($data[$otherContactField . '_fontFamily'] ?? '') ?: 'inherit',
        'font-weight' => ($data[$contactField . '_fontWeight'] ?? '') ?: ($data[$otherContactField . '_fontWeight'] ?? '') ?: 'bold',
        'text-align' => ($data[$contactField . '_textAlign'] ?? '') ?: ($data[$otherContactField . '_textAlign'] ?? '') ?: 'left',
        'letter-spacing' => ($data[$contactField . '_letterSpacing'] ?? '') ?: ($data[$otherContactField . '_letterSpacing'] ?? '') ?: 'normal',
        'line-height' => ($data[$contactField . '_lineHeight'] ?? '') ?: ($data[$otherContactField . '_lineHeight'] ?? '') ?: '1.625',
    ];
    $contactStyleStr = collect($contactStyles)->map(fn($v, $k) => "$k: $v")->join('; ');

    // Policies Styles
    $policiesField = 'footer_policies_' . $locale;
    $otherPoliciesField = 'footer_policies_' . $otherLocale;
    $policiesStyles = [
        'font-size' => ($data[$policiesField . '_fontSize'] ?? '') ?: ($data[$otherPoliciesField . '_fontSize'] ?? '') ?: 'inherit',
        'font-family' => ($data[$policiesField . '_fontFamily'] ?? '') ?: ($data[$otherPoliciesField . '_fontFamily'] ?? '') ?: 'inherit',
        'font-weight' => ($data[$policiesField . '_fontWeight'] ?? '') ?: ($data[$otherPoliciesField . '_fontWeight'] ?? '') ?: 'normal',
        'text-align' => ($data[$policiesField . '_textAlign'] ?? '') ?: ($data[$otherPoliciesField . '_textAlign'] ?? '') ?: 'left',
        'color' => ($data[$policiesField . '_color'] ?? '') ?: ($data[$otherPoliciesField . '_color'] ?? '') ?: 'inherit',
        'letter-spacing' => ($data[$policiesField . '_letterSpacing'] ?? '') ?: ($data[$otherPoliciesField . '_letterSpacing'] ?? '') ?: 'normal',
        'line-height' => ($data[$policiesField . '_lineHeight'] ?? '') ?: ($data[$otherPoliciesField . '_lineHeight'] ?? '') ?: '1.625',
    ];
    $policiesStyleStr = collect($policiesStyles)->filter(fn($v) => $v !== 'inherit')->map(fn($v, $k) => "$k: $v")->join('; ');
@endphp

<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-20">
    <div class="max-w-[1200px] mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <h2 class="text-slate-800 dark:text-white text-lg font-bold">
                        {{ $data['hotel_name'] ?? 'Hotel Andros' }}
                    </h2>
                </div>
                <p class="text-sm text-secondary font-bold leading-relaxed mb-6 {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                    {!! $editable !!} {!! $clickAction !!} data-field="footer_description_{{ app()->getLocale() }}"
                    data-label="Footer Description" @if($isEditor) :style="{
                        fontSize: getSectionData(getSectionIndex($el)).{{ 'footer_description_' . app()->getLocale() }}_fontSize || getSectionData(getSectionIndex($el)).{{ 'footer_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_fontSize || 'inherit',
                        fontFamily: getSectionData(getSectionIndex($el)).{{ 'footer_description_' . app()->getLocale() }}_fontFamily || getSectionData(getSectionIndex($el)).{{ 'footer_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_fontFamily || 'inherit',
                        fontWeight: getSectionData(getSectionIndex($el)).{{ 'footer_description_' . app()->getLocale() }}_fontWeight || getSectionData(getSectionIndex($el)).{{ 'footer_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_fontWeight || 'bold',
                        textAlign: getSectionData(getSectionIndex($el)).{{ 'footer_description_' . app()->getLocale() }}_textAlign || getSectionData(getSectionIndex($el)).{{ 'footer_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_textAlign || 'left',
                        letterSpacing: getSectionData(getSectionIndex($el)).{{ 'footer_description_' . app()->getLocale() }}_letterSpacing || getSectionData(getSectionIndex($el)).{{ 'footer_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_letterSpacing || 'normal',
                        lineHeight: getSectionData(getSectionIndex($el)).{{ 'footer_description_' . app()->getLocale() }}_lineHeight || getSectionData(getSectionIndex($el)).{{ 'footer_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_lineHeight || '1.625',
                    }" @endif style="{!! $descStyleStr !!}">
                    {{ app()->getLocale() == 'es' ? ($data['footer_description_es'] ?? 'Redefiniendo el viaje...') : ($data['footer_description_en'] ?? 'Redefining luxury...') }}
                </p>
            </div>
            <div>
                <h4 class="font-bold mb-6">{{ __('Enlaces Rápidos') }}</h4>
                <ul class="space-y-4 text-sm text-secondary font-bold">
                    <li><a class="hover:text-primary transition-colors"
                            href="#habitaciones">{{ __('Habitaciones') }}</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#contacto">{{ __('Ubicación') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">{{ __('Soporte') }}</h4>
                <ul class="space-y-4 text-sm text-secondary font-bold">
                    <li><a class="hover:text-primary transition-colors" href="#contacto">{{ __('Contacto') }}</a></li>
                    <li><a class="hover:text-primary transition-colors cursor-pointer" onclick="openPolicyModal()">{{ __('Políticas') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 text-slate-800 dark:text-slate-100">{{ __('Contáctenos') }}</h4>
                <p class="text-sm text-secondary font-bold mb-6 leading-relaxed {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                    {!! $editable !!} {!! $clickAction !!}
                    data-field="footer_contact_description_{{ app()->getLocale() }}" data-label="Contact Description"
                    @if($isEditor) :style="{
                        fontSize: getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . app()->getLocale() }}_fontSize || getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_fontSize || 'inherit',
                        fontFamily: getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . app()->getLocale() }}_fontFamily || getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_fontFamily || 'inherit',
                        fontWeight: getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . app()->getLocale() }}_fontWeight || getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_fontWeight || 'bold',
                        textAlign: getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . app()->getLocale() }}_textAlign || getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_textAlign || 'left',
                        letterSpacing: getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . app()->getLocale() }}_letterSpacing || getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_letterSpacing || 'normal',
                        lineHeight: getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . app()->getLocale() }}_lineHeight || getSectionData(getSectionIndex($el)).{{ 'footer_contact_description_' . (app()->getLocale() == 'es' ? 'en' : 'es') }}_lineHeight || '1.625',
                    }" @endif style="{!! $contactStyleStr !!}">
                    {{ app()->getLocale() == 'es' ? ($data['footer_contact_description_es'] ?? '¿Tienes alguna duda...') : ($data['footer_contact_description_en'] ?? 'Do you have questions...') }}
                </p>
                <button onclick="openContactModal()"
                    class="w-full bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">mail</span>
                    {{ __('Enviar Mensaje') }}
                </button>
            </div>
        </div>
        <div
            class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-secondary font-bold">
            <p>© <span {!! $editable !!} {!! $clickAction !!} data-field="footer_copyright"
                    data-label="Copyright Year">{{ $data['footer_copyright'] ?? date('Y') }}</span>
                {{ $data['hotel_name'] ?? 'Hotel Andros' }}.
                {{ app()->getLocale() == 'es' ? ($data['footer_copyright_es'] ?? 'Todos los derechos reservados.') : ($data['footer_copyright_en'] ?? 'All rights reserved.') }}
            </p>
            <div class="flex items-center gap-4">
                @php 
                    $iconSize = $data['social_icon_size'] ?? null;
                    $svgSize = $iconSize ?: 16;
                    $wrapperSize = $iconSize ? ($iconSize * 2) : 32;
                @endphp

                <!-- Facebook -->
                @php $hasFb = !empty($data['social_facebook']) && $data['social_facebook'] !== '#'; @endphp
                @if($isEditor || $hasFb)
                    <a href="{{ $hasFb ? $data['social_facebook'] : '#' }}" target="_blank"
                        class="rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-[#1877F2] hover:bg-[#1877F2]/10 transition-colors {{ $isEditor ? 'pointer-events-none' : '' }} {{ !$hasFb && $isEditor ? 'opacity-50 grayscale' : '' }}"
                        style="width: {{ $wrapperSize }}px; height: {{ $wrapperSize }}px;">
                        <svg class="fill-current" viewBox="0 0 24 24" style="width: {{ $svgSize }}px; height: {{ $svgSize }}px;">
                            <path
                                d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />
                        </svg>
                    </a>
                @endif

                <!-- Instagram -->
                @php $hasIg = !empty($data['social_instagram']) && $data['social_instagram'] !== '#'; @endphp
                @if($isEditor || $hasIg)
                    <a href="{{ $hasIg ? $data['social_instagram'] : '#' }}" target="_blank"
                        class="rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-[#E4405F] hover:bg-[#E4405F]/10 transition-colors {{ $isEditor ? 'pointer-events-none' : '' }} {{ !$hasIg && $isEditor ? 'opacity-50 grayscale' : '' }}"
                        style="width: {{ $wrapperSize }}px; height: {{ $wrapperSize }}px;">
                        <svg class="fill-current" viewBox="0 0 24 24" style="width: {{ $svgSize }}px; height: {{ $svgSize }}px;">
                            <path
                                d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z" />
                        </svg>
                    </a>
                @endif

                <!-- TripAdvisor -->
                @php $hasTa = !empty($data['social_tripadvisor']) && $data['social_tripadvisor'] !== '#'; @endphp
                @if($isEditor || $hasTa)
                    <a href="{{ $hasTa ? $data['social_tripadvisor'] : '#' }}" target="_blank"
                        class="rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-[#00af87] hover:bg-[#00af87]/10 transition-colors {{ $isEditor ? 'pointer-events-none' : '' }} {{ !$hasTa && $isEditor ? 'opacity-50 grayscale' : '' }}"
                        style="width: {{ $wrapperSize }}px; height: {{ $wrapperSize }}px;">
                        <svg class="fill-current" viewBox="0 0 24 24" style="width: {{ $svgSize }}px; height: {{ $svgSize }}px;">
                            <path
                                d="M12 12C9.24 12 7 9.76 7 7C7 4.24 9.24 2 12 2C14.76 2 17 4.24 17 7C17 9.76 14.76 12 12 12M12 4C10.35 4 9 5.35 9 7C9 8.65 10.35 10 12 10C13.65 10 15 8.65 15 7C15 5.35 13.65 4 12 4M5 14C2.24 14 0 16.24 0 19C0 21.76 2.24 24 5 24C7.76 24 10 21.76 10 19C10 16.24 7.76 14 5 14M5 22C3.35 22 2 20.65 2 19C2 17.35 3.35 16 5 16C6.65 16 8 17.35 8 19C8 20.65 6.65 22 5 22M19 14C16.24 14 14 16.24 14 19C14 21.76 16.24 24 19 24C21.76 24 24 21.76 24 19C24 16.24 21.76 14 19 14M19 22C17.35 22 16 20.65 16 19C16 17.35 17.35 16 19 16C20.65 16 22 17.35 22 19C22 20.65 20.65 22 19 22Z" />
                        </svg>
                    </a>
                @endif

                <!-- Dynamic Custom Links -->
                @php 
                    $rawCustomLinks = $data['custom_social_links'] ?? []; 
                    $customLinks = is_string($rawCustomLinks) ? json_decode($rawCustomLinks, true) : $rawCustomLinks;
                    if (!is_array($customLinks)) $customLinks = [];
                @endphp
                @foreach($customLinks as $link)
                    @php 
                        $url = $link['url'] ?? '';
                        $iconClass = $link['icon'] ?? 'fas fa-link';
                        $isValid = !empty($url) && $url !== '#';
                    @endphp
                    @if($isEditor || $isValid)
                        <a href="{{ $isValid ? $url : '#' }}" target="_blank" title="{{ $link['name'] ?? 'Red Social' }}"
                            class="rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-black dark:hover:text-white hover:bg-black/10 dark:hover:bg-white/10 transition-colors {{ $isEditor ? 'pointer-events-none' : '' }} {{ !$isValid && $isEditor ? 'opacity-50 grayscale' : '' }}"
                            style="width: {{ $wrapperSize }}px; height: {{ $wrapperSize }}px;">
                            <i class="{{ $iconClass }}" style="font-size: {{ $svgSize }}px; line-height: 1;"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contact-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeContactModal()"
                class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white" id="modal-title">
                            {{ __('Contáctenos') }}
                        </h3>
                        <button onclick="closeContactModal()"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <form id="contact-form" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Nombre Completo') }}</label>
                                <input type="text" name="name" required
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="{{ __('Tu nombre') }}">
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Correo Electrónico') }}</label>
                                <input type="email" name="email" required
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="{{ __('tu@correo.com') }}">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Asunto') }}</label>
                            <input type="text" name="subject" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-primary/50"
                                placeholder="{{ __('¿En qué podemos ayudarte?') }}">
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Mensaje') }}</label>
                            <textarea name="message" rows="4" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-primary/50 resize-none"
                                placeholder="{{ __('Escribe tu mensaje aquí...') }}"></textarea>
                        </div>

                        <div id="contact-response" class="hidden p-4 rounded-xl text-sm font-bold"></div>

                        <div class="pt-4">
                            <button type="submit" id="contact-submit"
                                class="w-full bg-primary text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-sm shadow-xl shadow-primary/30 hover:bg-primary/90 transition-all flex items-center justify-center gap-3">
                                <span>{{ __('Enviar Mensaje') }}</span>
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Area -->
    @if($isEditor)
        <div id="editor-policy-block" class="mt-12 p-8 bg-slate-50 dark:bg-slate-800/50 rounded-3xl border border-slate-200 dark:border-slate-700">
            <div class="mb-6">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4 inline-block">Editor View</span>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                    {{ app()->getLocale() == 'es' ? 'Políticas del Hotel' : 'Hotel Policies' }}
                </h3>
            </div>
            <div {!! $editable !!} {!! $clickAction !!} data-field="footer_policies_{{ app()->getLocale() }}" data-label="{{ app()->getLocale() == 'es' ? 'Políticas del Hotel' : 'Hotel Policies' }}"
                :style="{
                    fontSize: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_fontSize || 'inherit',
                    fontFamily: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_fontFamily || 'inherit',
                    fontWeight: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_fontWeight || 'normal',
                    textAlign: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_textAlign || 'left',
                    color: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_color || 'inherit',
                    letterSpacing: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_letterSpacing || 'normal',
                    lineHeight: getSectionData(getSectionIndex($el)).{{ 'footer_policies_' . app()->getLocale() }}_lineHeight || '1.625',
                }" 
                @keydown.enter.prevent style="{!! $policiesStyleStr !!}"
                class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 text-sm leading-relaxed pr-4 hover:ring-2 ring-blue-500 rounded px-2 min-h-[100px]">
                {!! app()->getLocale() == 'es' ? ($data['footer_policies_es'] ?? __('Políticas no configuradas.')) : ($data['footer_policies_en'] ?? __('Policies not configured.')) !!}
            </div>
        </div>
    @else
        <!-- Privacy Policy Modal -->
        <div id="policy-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="policy-modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div onclick="closePolicyModal()"
                    class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="p-8 md:p-12">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-3xl font-black text-slate-800 dark:text-white" id="policy-modal-title">
                                {{ app()->getLocale() == 'es' ? 'Políticas del Hotel' : 'Hotel Policies' }}
                            </h3>
                            <button onclick="closePolicyModal()"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors bg-slate-100 dark:bg-slate-800 p-2 rounded-full">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div style="{!! $policiesStyleStr !!}"
                            class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 text-sm leading-relaxed max-h-[60vh] overflow-y-auto pr-4 custom-scrollbar">
                            {!! app()->getLocale() == 'es' ? ($data['footer_policies_es'] ?? __('Políticas no configuradas.')) : ($data['footer_policies_en'] ?? __('Policies not configured.')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openPolicyModal() {
            const modal = document.getElementById('policy-modal');
            if(modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                const inlineBlock = document.getElementById('editor-policy-block');
                if (inlineBlock) {
                    inlineBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }

        function closePolicyModal() {
            const modal = document.getElementById('policy-modal');
            if(modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function openContactModal() {
            const modal = document.getElementById('contact-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeContactModal() {
            const modal = document.getElementById('contact-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('contact-form')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('contact-submit');
            const responseDiv = document.getElementById('contact-response');
            const formData = new FormData(form);

            // UI Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin material-symbols-outlined">sync</span> {{ __("Enviando...") }}';
            responseDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("contact.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                responseDiv.classList.remove('hidden');
                if (response.ok) {
                    responseDiv.className = 'p-4 rounded-xl text-sm font-bold bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 mb-6';
                    responseDiv.textContent = result.message;
                    form.reset();
                    setTimeout(closeContactModal, 3000);
                } else {
                    responseDiv.className = 'p-4 rounded-xl text-sm font-bold bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 mb-6';
                    responseDiv.textContent = result.message || "{{ __('Error en la validación') }}";
                }
            } catch (error) {
                responseDiv.classList.remove('hidden');
                responseDiv.className = 'p-4 rounded-xl text-sm font-bold bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 mb-6';
                responseDiv.textContent = "{{ __('Hubo un problema de conexión. Inténtalo de nuevo.') }}";
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>{{ __("Enviar Mensaje") }}</span><span class="material-symbols-outlined">send</span>';
            }
        });
    </script>
</footer>