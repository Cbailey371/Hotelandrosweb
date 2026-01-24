<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-20">
    <div class="max-w-[1200px] mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <h2 class="text-slate-800 dark:text-white text-lg font-bold">
                        {{ $settings['hotel_name'] ?? 'Hotel Andros' }}
                    </h2>
                </div>
                <p class="text-sm text-secondary font-bold leading-relaxed mb-6">
                    {{ app()->getLocale() == 'es' ? ($settings['footer_description_es'] ?? __('Redefiniendo el viaje de lujo desde 1994. Experimente una hospitalidad inigualable y un confort excepcional.')) : ($settings['footer_description_en'] ?? __('Redefining the luxury travel since 1994. Experience unparalleled hospitality and exceptional comfort.')) }}
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
                    <li><a class="hover:text-primary transition-colors cursor-pointer"
                            onclick="openPolicyModal()">{{ __('Políticas') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 text-slate-800 dark:text-slate-100">{{ __('Contáctenos') }}</h4>
                <p class="text-sm text-secondary font-bold mb-6 leading-relaxed">
                    {{ app()->getLocale() == 'es' ? ($settings['footer_contact_description_es'] ?? __('¿Tienes alguna duda o requerimiento especial? Estamos aquí para ayudarte.')) : ($settings['footer_contact_description_en'] ?? __('Do you have any questions or special requirements? We are here to help.')) }}
                </p>
                <button onclick="openContactModal()"
                    class="w-full bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">mail</span>
                    {{ __('Enviar Mensaje') }}
                </button>
            </div>
        </div>

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
                                {{ __('Políticas del Hotel') }}
                            </h3>
                            <button onclick="closePolicyModal()"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors bg-slate-100 dark:bg-slate-800 p-2 rounded-full">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div
                            class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 text-sm leading-relaxed max-h-[60vh] overflow-y-auto pr-4 custom-scrollbar">
                            {!! app()->getLocale() == 'es' ? ($settings['footer_policies_es'] ?? __('Políticas no configuradas.')) : ($settings['footer_policies_en'] ?? __('Policies not configured.')) !!}
                        </div>
                    </div>
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

        <script>
            function openPolicyModal() {
                const modal = document.getElementById('policy-modal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closePolicyModal() {
                const modal = document.getElementById('policy-modal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
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
        <div
            class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-secondary font-bold">
            <p>© {{ date('Y') }} {{ $settings['hotel_name'] ?? 'Hotel Andros' }}.
                {{ app()->getLocale() == 'es' ? ($settings['footer_copyright_es'] ?? __('Todos los derechos reservados.')) : ($settings['footer_copyright_en'] ?? __('All rights reserved.')) }}
            </p>
            <div class="flex gap-4">
                @php
                    $socialLinks = json_decode($settings['footer_socials_json'] ?? '[]', true);
                    $iconMap = [
                        'instagram' => 'fa-brands fa-instagram',
                        'facebook' => 'fa-brands fa-facebook-f',
                        'facebook-f' => 'fa-brands fa-facebook-f',
                        'linkedin' => 'fa-brands fa-linkedin-in',
                        'twitter' => 'fa-brands fa-x-twitter',
                        'tiktok' => 'fa-brands fa-tiktok',
                        'youtube' => 'fa-brands fa-youtube',
                        'whatsapp' => 'fa-brands fa-whatsapp'
                    ];
                @endphp
                @if(is_array($socialLinks) && count($socialLinks) > 0)
                    @foreach($socialLinks as $link)
                        @if($link['active'])
                            @php
                                $isInstagram = strtolower($link['platform']) === 'instagram';
                            @endphp
                            <a class="flex items-center gap-3 group" href="{{ $link['url'] }}" target="_blank" title="{{ $link['platform'] }}">
                                @if($isInstagram)
                                    <span class="text-[10px] font-black uppercase tracking-widest text-secondary group-hover:text-primary transition-colors">Instagram</span>
                                @endif
                                <div class="w-10 h-10 {{ $isInstagram ? 'w-12 h-12' : '' }} flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 {{ $isInstagram ? 'text-2xl' : 'text-lg' }} shadow-sm">
                                    <i class="{{ $iconMap[strtolower($link['platform'])] ?? 'fa-solid fa-link' }}"></i>
                                </div>
                            </a>
                        @endif
                    @endforeach
                @else
                    {{-- Default icons if no JSON config exists --}}
                    @php
                        $defaults = [
                            ['platform' => 'instagram', 'url' => $settings['footer_instagram'] ?? '#'],
                            ['platform' => 'facebook', 'url' => $settings['footer_facebook'] ?? '#'],
                            ['platform' => 'linkedin', 'url' => $settings['footer_linkedin'] ?? '#'],
                        ];
                    @endphp
                    @foreach($defaults as $def)
                        @php
                            $isInstagram = strtolower($def['platform']) === 'instagram';
                        @endphp
                        <a class="flex items-center gap-3 group" href="{{ $def['url'] }}" target="_blank" title="{{ $def['platform'] }}">
                            @if($isInstagram)
                                <span class="text-[10px] font-black uppercase tracking-widest text-secondary group-hover:text-primary transition-colors">Instagram</span>
                            @endif
                            <div class="w-10 h-10 {{ $isInstagram ? 'w-12 h-12' : '' }} flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 {{ $isInstagram ? 'text-2xl' : 'text-lg' }} shadow-sm">
                                <i class="{{ $iconMap[$def['platform']] ?? 'fa-solid fa-link' }}"></i>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</footer>