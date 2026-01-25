<html class="{{ ($settings['website_theme'] ?? 'light') == 'dark' ? 'dark' : 'light' }}"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name') }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <meta name="theme-color" content="#137fec">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Manifest PWA -->
    <link rel="manifest" href="/manifest.json">


    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-color: {{ $settings['primary_color'] ?? '#137fec' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#4c739a' }};
            --contrast-level: {{ ($settings['text_contrast_level'] ?? 50) / 100 }};
        }

        @if(($settings['high_contrast'] ?? '0') == '1')
            :root { --contrast-level: 1; }
        @endif

        .text-primary { color: var(--primary-color); }
        .bg-primary { background-color: var(--primary-color); }
        .bg-primary\/10 { background-color: rgba(19, 127, 236, 0.1); }
        .peer-checked\:border-primary:checked + div,
        .peer-checked\:border-primary:checked ~ div { border-color: var(--primary-color) !important; }
        .peer-checked\:bg-primary:checked + div,
        .peer-checked\:bg-primary:checked ~ div { background-color: var(--primary-color) !important; }
        .peer-checked\:bg-primary\/5:checked + div,
        .peer-checked\:bg-primary\/5:checked ~ div { background-color: color-mix(in srgb, var(--primary-color), transparent 95%) !important; }
        .accent-primary { accent-color: var(--primary-color); }

        /* Dynamic Contrast Adjustments for Admin */
        .text-slate-600, .text-slate-500, .text-slate-700, .text-slate-800, p, span, h1, h2, h3, h4, h5, h6 {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 - var(--contrast-level) * 0.1));
        }

        .dark .text-slate-400, .dark .text-slate-300, .dark .text-slate-50, .dark .text-white,
        .dark p, .dark span, .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 + var(--contrast-level) * 0.2));
        }

        @if(($settings['high_contrast'] ?? '0') == '1')
            .text-slate-600, .text-slate-500, .text-slate-700, .text-slate-800, p, h1, h2, h3, h4, h5, h6 { color: #000 !important; }
            .dark .text-slate-400, .dark .text-slate-300, .dark .text-slate-50, .dark .text-white, .dark p, .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 { color: #fff !important; }
        @endif

        /* Smooth transitions */
        html, body, p, span, h1, h2, h3, h4, h5, h6, div, article, aside, section {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1), filter 0.4s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#f6f7f8] dark:bg-[#06070a] text-[#0d141b] dark:text-slate-50">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-[#f6f7f8] dark:bg-[#06070a]">
            <header
                class="sticky top-0 z-10 flex items-center justify-between border-b border-[#e7edf3] dark:border-slate-800 bg-white/80 dark:bg-[#06070a]/80 backdrop-blur-md px-8 py-4">
                <h2 class="text-[#0d141b] dark:text-white text-xl font-bold tracking-tight">@yield('header')</h2>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-[#0d141b] dark:text-white leading-none">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-[10px] font-medium text-[#4c739a] uppercase tracking-wider mt-1">
                                Administrator</p>
                        </div>
                        <div
                            class="h-10 w-10 rounded-full bg-slate-200 border-2 border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#4c739a]">person</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8 max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
    <!-- Service Worker Registration -->
    72:
    <!--
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    -->
    @stack('scripts')
</body>

</html>