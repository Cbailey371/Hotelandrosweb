@props(['data', 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = $isEditor ? '@click.stop="$store.editor.selectElement($el)"' : '';
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
                    data-label="Footer Description">
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
                    <li><a class="hover:text-primary transition-colors cursor-pointer">{{ __('Políticas') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 text-slate-800 dark:text-slate-100">{{ __('Contáctenos') }}</h4>
                <p class="text-sm text-secondary font-bold mb-6 leading-relaxed {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                    {!! $editable !!} {!! $clickAction !!}
                    data-field="footer_contact_description_{{ app()->getLocale() }}" data-label="Contact Description">
                    {{ app()->getLocale() == 'es' ? ($data['footer_contact_description_es'] ?? '¿Tienes alguna duda...') : ($data['footer_contact_description_en'] ?? 'Do you have questions...') }}
                </p>
                <button
                    class="w-full bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">mail</span>
                    {{ __('Enviar Mensaje') }}
                </button>
            </div>
        </div>
        <div
            class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-secondary font-bold">
            <p>© {{ date('Y') }} {{ $data['hotel_name'] ?? 'Hotel Andros' }}.
                {{ app()->getLocale() == 'es' ? ($data['footer_copyright_es'] ?? 'Todos los derechos reservados.') : ($data['footer_copyright_en'] ?? 'All rights reserved.') }}
            </p>
        </div>
    </div>
</footer>