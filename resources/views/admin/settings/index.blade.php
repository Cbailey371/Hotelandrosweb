@extends('layouts.admin')

@section('header', 'Configuración General')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8"
            id="settings-form">
            @csrf

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Theme & Branding Settings -->
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">palette</span>
                        Theme & Branding Settings
                    </h3>
                </div>

                <div class="p-8 space-y-12">
                    <!-- Base Website Theme -->
                    <div class="space-y-6">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Base Website Theme</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="website_theme" value="light" class="peer hidden" {{ ($settings['website_theme'] ?? 'light') == 'light' ? 'checked' : '' }}>
                                <div
                                    class="p-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 peer-checked:border-primary peer-checked:bg-primary/5 transition-all peer-checked:[&_.radio-circle]:border-primary peer-checked:[&_.radio-dot]:opacity-100 peer-checked:[&_.radio-dot]:scale-100">
                                    <div class="flex justify-between items-start mb-4">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                                            <span class="material-symbols-outlined">light_mode</span>
                                        </div>
                                        <div
                                            class="radio-circle w-6 h-6 rounded-full border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center bg-white dark:bg-slate-800 transition-all">
                                            <div
                                                class="radio-dot w-3 h-3 rounded-full bg-primary opacity-0 transition-all scale-50">
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="font-bold text-slate-800 dark:text-white">Light Mode</h5>
                                    <p class="text-xs text-slate-500">Standard bright interface</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="website_theme" value="dark" class="peer hidden" {{ ($settings['website_theme'] ?? 'light') == 'dark' ? 'checked' : '' }}>
                                <div
                                    class="p-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 peer-checked:border-primary peer-checked:bg-primary/5 transition-all peer-checked:[&_.radio-circle]:border-primary peer-checked:[&_.radio-dot]:opacity-100 peer-checked:[&_.radio-dot]:scale-100">
                                    <div class="flex justify-between items-start mb-4">
                                        <div
                                            class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-300">
                                            <span class="material-symbols-outlined">dark_mode</span>
                                        </div>
                                        <div
                                            class="radio-circle w-6 h-6 rounded-full border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center bg-white dark:bg-slate-800 transition-all">
                                            <div
                                                class="radio-dot w-3 h-3 rounded-full bg-primary opacity-0 transition-all scale-50">
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="font-bold text-slate-800 dark:text-white">Dark Mode</h5>
                                    <p class="text-xs text-slate-500">Elegant dark aesthetic</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Brand Colors -->
                    <div class="space-y-6">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Brand Colors</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Primary
                                    Color</label>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                                        <input type="color" id="primary_picker"
                                            value="{{ $settings['primary_color'] ?? '#1173d4' }}"
                                            class="absolute inset-[-10px] w-[200%] h-[200%] cursor-pointer"
                                            oninput="document.getElementById('primary_hex').value = this.value">
                                    </div>
                                    <input type="text" id="primary_hex" name="primary_color"
                                        value="{{ $settings['primary_color'] ?? '#1173d4' }}"
                                        class="flex-1 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-mono uppercase focus:ring-2 focus:ring-primary/50"
                                        oninput="document.getElementById('primary_picker').value = this.value">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2">Used for buttons, active states, and accents.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Secondary
                                    Color</label>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                                        <input type="color" id="secondary_picker"
                                            value="{{ $settings['secondary_color'] ?? '#0EA5E9' }}"
                                            class="absolute inset-[-10px] w-[200%] h-[200%] cursor-pointer"
                                            oninput="document.getElementById('secondary_hex').value = this.value">
                                    </div>
                                    <input type="text" id="secondary_hex" name="secondary_color"
                                        value="{{ $settings['secondary_color'] ?? '#0EA5E9' }}"
                                        class="flex-1 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-mono uppercase focus:ring-2 focus:ring-primary/50"
                                        oninput="document.getElementById('secondary_picker').value = this.value">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2">Used for highlights and supplementary icons.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Dark Mode
                                    Background</label>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="relative w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                                        <input type="color" id="dark_mode_picker"
                                            value="{{ $settings['dark_mode_color'] ?? '#06070a' }}"
                                            class="absolute inset-[-10px] w-[200%] h-[200%] cursor-pointer"
                                            oninput="document.getElementById('dark_mode_hex').value = this.value">
                                    </div>
                                    <input type="text" id="dark_mode_hex" name="dark_mode_color"
                                        value="{{ $settings['dark_mode_color'] ?? '#06070a' }}"
                                        class="flex-1 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-mono uppercase focus:ring-2 focus:ring-primary/50"
                                        oninput="document.getElementById('dark_mode_picker').value = this.value">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2">The core background color for Dark Mode.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Accessibility & Contrast -->
                    <div class="space-y-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Accessibility & Contrast
                        </h4>
                        <div class="space-y-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-bold text-slate-800 dark:text-white text-sm">High Contrast Text</h5>
                                    <p class="text-xs text-slate-500">Forces darker text for better readability</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="high_contrast" value="0">
                                    <input type="checkbox" name="high_contrast" value="1" class="sr-only peer" {{ ($settings['high_contrast'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h5 class="font-bold text-slate-800 dark:text-white text-sm">Text Contrast Level</h5>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subtle —
                                        Strong</span>
                                </div>
                                <input type="range" name="text_contrast_level" min="0" max="100"
                                    value="{{ $settings['text_contrast_level'] ?? '50' }}"
                                    class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity Visual (Logos) -->
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">id_card</span>
                    Visual Assets
                </h3>
                <div class="grid grid-cols-1 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Nombre del Hotel</label>
                        <input type="text" name="hotel_name" value="{{ $settings['hotel_name'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-8">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">SEO & Meta Tags</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Spanish SEO -->
                        <div class="space-y-6">
                            <h5 class="text-[10px] font-black uppercase text-slate-400 flex items-center gap-2">
                                <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded">ES</span>
                                Versión Español
                            </h5>
                            <div>
                                <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Meta Descripción (ES)</label>
                                <textarea name="website_description" rows="3"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="Descripción en español...">{{ $settings['website_description'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Keywords (ES)</label>
                                <input type="text" name="website_keywords" value="{{ $settings['website_keywords'] ?? '' }}"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="hotel, colon, panama...">
                            </div>
                        </div>

                        <!-- English SEO -->
                        <div class="space-y-6">
                            <h5 class="text-[10px] font-black uppercase text-slate-400 flex items-center gap-2">
                                <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded">EN</span>
                                English Version
                            </h5>
                            <div>
                                <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Meta Description (EN)</label>
                                <textarea name="website_description_en" rows="3"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="English description...">{{ $settings['website_description_en'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Keywords (EN)</label>
                                <input type="text" name="website_keywords_en" value="{{ $settings['website_keywords_en'] ?? '' }}"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                    placeholder="hotel, luxury, panama...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Logo Hotel</label>
                        <div class="relative group aspect-square">
                            <img src="{{ $settings['hotel_logo'] ?? '/images/branding/logo.png' }}" id="logo-preview"
                                class="w-full h-full object-contain rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50">
                            <label
                                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-xl">
                                <span class="text-white text-[10px] font-bold">Cambiar Logo</span>
                                <input type="file" name="hotel_logo" class="hidden"
                                    onchange="previewImage(this, 'logo-preview')">
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Favicon</label>
                        <div class="relative group aspect-square">
                            <img src="{{ $settings['hotel_favicon'] ?? '/favicon.ico' }}" id="favicon-preview"
                                class="w-full h-full object-contain rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50">
                            <label
                                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-xl">
                                <span class="text-white text-[10px] font-bold">Cambiar</span>
                                <input type="file" name="hotel_favicon" class="hidden"
                                    onchange="previewImage(this, 'favicon-preview')">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMTP Configuration -->
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        Configuración de Correo (SMTP)
                    </h3>
                    <div class="flex items-center gap-2">
                        <input type="email" id="test-email-address" placeholder="Email para prueba"
                            class="bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-1.5 text-xs w-48">
                        <button type="button" onclick="sendTestEmail()" id="test-btn"
                            class="px-4 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 transition-all">
                            Probar Envío
                        </button>
                    </div>
                </div>

                <div id="test-result" class="hidden text-xs p-3 rounded-lg mb-4"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Servidor SMTP (Host)</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Puerto</label>
                        <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Encriptación</label>
                        <select name="mail_encryption"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS
                            </option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL
                            </option>
                            <option value="null" {{ ($settings['mail_encryption'] ?? '') == 'null' ? 'selected' : '' }}>
                                Ninguna</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Usuario SMTP</label>
                        <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Contraseña SMTP</label>
                        <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Email Remitente (From)</label>
                        <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#4c739a] mb-2">Nombre Remitente</label>
                        <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                </div>
            </div>

            <!-- Email Templates -->
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-8">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit_note</span>
                    Personalización de Correos Electrónicos
                </h3>

                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800">
                    <p class="text-xs text-blue-700 dark:text-blue-300 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">info</span>
                        Puedes usar estas etiquetas que se reemplazarán automáticamente:
                    </p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach(['{cliente}', '{habitacion}', '{check_in}', '{check_out}', '{huespedes}', '{referencia}', '{email}', '{telefono}', '{pais}', '{mensaje}', '{hotel}'] as $tag)
                            <span
                                class="px-2 py-1 bg-white dark:bg-slate-800 rounded-md text-[10px] font-mono font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-10">
                    <!-- Confirmación de Solicitud -->
                    <div class="space-y-6">
                        <h4
                            class="text-xs font-black text-slate-400 uppercase tracking-widest border-l-4 border-primary pl-3">
                            1. Correo de Recibo de Solicitud (Inmediato)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Versión Español -->
                            <div class="space-y-4">
                                <h5 class="text-[10px] font-black uppercase text-slate-400">Versión Español (ES)</h5>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Asunto</label>
                                    <input type="text" name="mail_confirmation_subject"
                                        value="{{ $settings['mail_confirmation_subject'] ?? 'Confirmación de solicitud de reserva - Hotel Andros' }}"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Mensaje</label>
                                    <textarea name="mail_confirmation_body" rows="4"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                        placeholder="Gracias por elegirnos...">{{ $settings['mail_confirmation_body'] ?? 'Gracias por elegirnos. A continuación te presentamos el resumen de tu solicitud. Nos pondremos en contacto contigo a la brevedad para confirmar la disponibilidad y finalizar tu reserva.' }}</textarea>
                                </div>
                            </div>

                            <!-- Versión Inglés -->
                            <div class="space-y-4">
                                <h5 class="text-[10px] font-black uppercase text-slate-400">English Version (EN)</h5>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Subject</label>
                                    <input type="text" name="mail_confirmation_subject_en"
                                        value="{{ $settings['mail_confirmation_subject_en'] ?? 'Booking Request Confirmation - Hotel Andros' }}"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Body
                                        Message</label>
                                    <textarea name="mail_confirmation_body_en" rows="4"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                        placeholder="Thank you for choosing us...">{{ $settings['mail_confirmation_body_en'] ?? 'Thank you for choosing us. Below is the summary of your request. Please note that availability is not confirmed until we contact you.' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmación de Reserva (Procesada) -->
                    <div class="space-y-6">
                        <h4
                            class="text-xs font-black text-slate-400 uppercase tracking-widest border-l-4 border-green-500 pl-3">
                            2. Correo de Reserva Confirmada (Desde el Panel)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Versión Español -->
                            <div class="space-y-4">
                                <h5 class="text-[10px] font-black uppercase text-slate-400">Versión Español (ES)</h5>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Asunto</label>
                                    <input type="text" name="mail_processed_subject"
                                        value="{{ $settings['mail_processed_subject'] ?? 'Reserva Confirmada - Hotel Andros' }}"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Mensaje</label>
                                    <textarea name="mail_processed_body" rows="4"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                        placeholder="Es un placer informarte...">{{ $settings['mail_processed_body'] ?? 'Es un placer informarte que tu solicitud de reserva ha sido procesada y confirmada por nuestro equipo de recepción. A continuación encontrarás los detalles finales de tu estancia:' }}</textarea>
                                </div>
                            </div>

                            <!-- Versión Inglés -->
                            <div class="space-y-4">
                                <h5 class="text-[10px] font-black uppercase text-slate-400">English Version (EN)</h5>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Subject</label>
                                    <input type="text" name="mail_processed_subject_en"
                                        value="{{ $settings['mail_processed_subject_en'] ?? 'Booking Confirmed - Hotel Andros' }}"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Body
                                        Message</label>
                                    <textarea name="mail_processed_body_en" rows="4"
                                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                        placeholder="It is a pleasure to inform you...">{{ $settings['mail_processed_body_en'] ?? 'It is a pleasure to inform you that your booking request has been processed and confirmed by our reception team. Below you will find the final details of your stay:' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Común -->
                    <div class="pt-8 border-t border-slate-100 dark:border-slate-800 space-y-8">
                        <h4
                            class="text-xs font-black text-slate-400 uppercase tracking-widest border-l-4 border-slate-300 pl-3">
                            3. Información Común (Pie de Página)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Nota de Contacto ES -->
                            <div>
                                <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Nota de Contacto
                                    (ES)</label>
                                <textarea name="mail_footer_note" rows="2"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm"
                                    placeholder="Si tienes alguna duda...">{{ $settings['mail_footer_note'] ?? 'Si tienes alguna duda, puedes responder directamente a este correo o contactarnos por los canales oficiales del hotel.' }}</textarea>
                            </div>
                            <!-- Nota de Contacto EN -->
                            <div>
                                <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Contact Note
                                    (EN)</label>
                                <textarea name="mail_footer_note_en" rows="2"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm"
                                    placeholder="If you have any questions...">{{ $settings['mail_footer_note_en'] ?? 'If you have any questions, you can reply directly to this email or contact us through the hotel official channels.' }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Ubicación /
                                Location</label>
                            <input type="text" name="mail_footer_location"
                                value="{{ $settings['mail_footer_location'] ?? 'Panamá, Colon' }}"
                                class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm"
                                placeholder="Ciudad, País">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">contact_phone</span>
                    Información de Contacto Pública
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">WhatsApp (Sin +)</label>
                        <input type="text" name="hotel_whatsapp" value="{{ $settings['hotel_whatsapp'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Email de Recepción (Notificaciones)</label>
                        <input type="text" name="hotel_email" value="{{ $settings['hotel_email'] ?? '' }}"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm"
                            placeholder="ejemplo@hotel.com, admin@hotel.com">
                        <p class="text-[10px] text-slate-400 mt-2">Puedes agregar varios correos separados por comas. A estos correos llegarán las reservas y consultas.</p>
                    </div>
                </div>
            </div>

            <!-- System Maintenance Section -->
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500">terminal</span>
                    Mantenimiento del Sistema
                </h3>
                <p class="text-xs text-slate-500">Acciones críticas de limpieza y reparación del servidor.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <h4 class="text-sm font-bold mb-2">Limpieza de Caché Profunda</h4>
                        <p class="text-[11px] text-slate-500 mb-4">Elimina la caché de vistas, configuración y rutas. Útil
                            si los cambios no se reflejan.</p>
                        <button type="button"
                            onclick="confirmAction('{{ route('admin.clear-cache') }}', '¿Limpiar toda la caché del sistema?')"
                            class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">cleaning_services</span>
                            Ejecutar Limpieza
                        </button>
                    </div>

                    <div
                        class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                        <h4 class="text-sm font-bold mb-2">Reparación Maestra (SSL/Assets)</h4>
                        <p class="text-[11px] text-slate-500 mb-4">Corrige errores de carga de estilos y conflictos de
                            archivos temporales (Vite/Hot).</p>
                        <button type="button"
                            onclick="confirmAction('{{ route('admin.repair-ssl') }}', '¿Ejecutar reparación maestra?')"
                            class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">build</span>
                            Reparar Sistema
                        </button>
                    </div>
                </div>

                <!-- Info cPanel sin SSH -->
                <div class="mt-8 p-6 rounded-2xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/50">
                    <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">info</span>
                        Guía para Carga de Cambios (cPanel sin SSH)
                    </h4>
                    <p class="text-[11px] text-blue-700 dark:text-blue-400 mb-4">
                        Si has subido archivos nuevos y necesitas que el sitio se actualice, usa esta ruta especial en tu navegador:
                    </p>
                    <div class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-blue-100 dark:border-blue-900 flex items-center justify-between">
                        <code class="text-[10px] font-mono text-primary">{{ url('/cpanel-setup/full-setup') }}</code>
                        <a href="{{ url('/cpanel-setup/full-setup') }}" target="_blank" class="text-[10px] font-black uppercase tracking-widest text-primary hover:underline">Ejecutar Ahora</a>
                    </div>
                    <p class="text-[9px] text-slate-400 mt-3 italic">
                        * Esta ruta ejecutará migraciones, actualizará los enlaces de fotos y optimizará el servidor en un solo paso.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-10 py-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 transition-all">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-10 py-3 rounded-lg bg-primary text-white font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
                    Guardar Todas las Configuraciones
                </button>
            </div>
        </form>
    </div>

    <!-- Hidden form for POST maintenance actions -->
    <form id="maintenance-form" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Live Preview for Accessibility, Contrast, Theme & Colors
        document.addEventListener('DOMContentLoaded', () => {
            const contrastSlider = document.querySelector('input[name="text_contrast_level"]');
            const highContrastToggle = document.querySelector('input[name="high_contrast"]');
            const primaryPicker = document.getElementById('primary_picker');
            const primaryHex = document.getElementById('primary_hex');
            const secondaryPicker = document.getElementById('secondary_picker');
            const secondaryHex = document.getElementById('secondary_hex');
            const themeRadios = document.querySelectorAll('input[name="website_theme"]');

            // Theme Preview
            themeRadios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    if (e.target.value === 'dark') {
                        document.documentElement.classList.add('dark');
                        document.documentElement.classList.remove('light');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.documentElement.classList.add('light');
                    }
                });
            });

            // Color Preview
            if (primaryPicker) {
                primaryPicker.addEventListener('input', (e) => {
                    document.documentElement.style.setProperty('--primary-color', e.target.value);
                });
            }
            if (primaryHex) {
                primaryHex.addEventListener('input', (e) => {
                    if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                        document.documentElement.style.setProperty('--primary-color', e.target.value);
                    }
                });
            }
            if (secondaryPicker) {
                secondaryPicker.addEventListener('input', (e) => {
                    document.documentElement.style.setProperty('--secondary-color', e.target.value);
                });
            }
            if (secondaryHex) {
                secondaryHex.addEventListener('input', (e) => {
                    if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                        document.documentElement.style.setProperty('--secondary-color', e.target.value);
                    }
                });
            }

            // Dark Mode Color Preview
            const darkModePicker = document.getElementById('dark_mode_picker');
            const darkModeHex = document.getElementById('dark_mode_hex');

            if (darkModePicker) {
                darkModePicker.addEventListener('input', (e) => {
                    document.documentElement.style.setProperty('--dark-bg-color', e.target.value);
                    // Update immediate styles if dark mode is active
                    if (document.documentElement.classList.contains('dark')) {
                        document.body.style.backgroundColor = e.target.value;
                    }
                });
            }
            if (darkModeHex) {
                darkModeHex.addEventListener('input', (e) => {
                    if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                        document.documentElement.style.setProperty('--dark-bg-color', e.target.value);
                        if (document.documentElement.classList.contains('dark')) {
                            document.body.style.backgroundColor = e.target.value;
                        }
                    }
                });
            }

            // Contrast Preview
            if (contrastSlider) {
                contrastSlider.addEventListener('input', (e) => {
                    const value = e.target.value / 100;
                    document.documentElement.style.setProperty('--contrast-level', value);
                });
            }

            if (highContrastToggle) {
                highContrastToggle.addEventListener('change', (e) => {
                    if (e.target.checked) {
                        document.documentElement.style.setProperty('--contrast-level', '1');
                    } else {
                        const val = contrastSlider ? contrastSlider.value / 100 : 0.5;
                        document.documentElement.style.setProperty('--contrast-level', val);
                    }
                });
            }
        });

        async function sendTestEmail() {
            const btn = document.getElementById('test-btn');
            const resultDiv = document.getElementById('test-result');
            const testEmail = document.getElementById('test-email-address').value;

            if (!testEmail) {
                alert('Por favor, ingresa un email para la prueba.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = 'Enviando...';
            resultDiv.classList.add('hidden');

            // Recopilamos datos actuales del form para la prueba sin necesidad de guardar primero
            const formData = new FormData(document.getElementById('settings-form'));
            formData.append('test_email', testEmail);

            try {
                const response = await fetch("{{ route('admin.settings.test-email') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                resultDiv.classList.remove('hidden');
                if (data.success) {
                    resultDiv.className = 'text-xs p-3 rounded-lg mb-4 bg-green-100 text-green-700 border border-green-200';
                    resultDiv.innerHTML = '✅ ' + data.message;
                } else {
                    resultDiv.className = 'text-xs p-3 rounded-lg mb-4 bg-red-100 text-red-700 border border-red-200';
                    resultDiv.innerHTML = '❌ ' + data.message;
                }
            } catch (error) {
                resultDiv.classList.remove('hidden');
                resultDiv.className = 'text-xs p-3 rounded-lg mb-4 bg-red-100 text-red-700 border border-red-200';
                resultDiv.innerHTML = '❌ Error de conexión: ' + error.message;
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Probar Envío';
            }
        }
        function confirmAction(url, message) {
            if (confirm(message)) {
                const form = document.getElementById('maintenance-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection