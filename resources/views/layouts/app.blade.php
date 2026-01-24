<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="{{ ($settings['website_theme'] ?? 'light') == 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $settings['primary_color'] ?? '#137fec' }}">

    <meta name="description"
        content="{{ $settings['website_description'] ?? 'Hospedaje de calidad en el corazón de Colón.' }}">
    <meta name="keywords" content="{{ $settings['website_keywords'] ?? 'hotel, colon, panama, andros' }}">
    <meta name="author" content="{{ $settings['hotel_name'] ?? 'Hotel Andros' }}">

    <title>@yield('title') - {{ $settings['hotel_name'] ?? config('app.name', 'Laravel') }}</title>

    <!-- Manifest PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/x-icon" href="{{ $settings['hotel_favicon'] ?? '/favicon.ico' }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- App Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --primary-color:
                {{ $settings['primary_color'] ?? '#137fec' }}
            ;
            --secondary-color:
                {{ $settings['secondary_color'] ?? '#4c739a' }}
            ;
            --contrast-level:
                {{ ($settings['text_contrast_level'] ?? 50) / 100 }}
            ;
        }

        @if(($settings['high_contrast'] ?? '0') == '1')
            :root {
                --contrast-level: 1;
            }

        @endif

        /* Dynamic Contrast Adjustments */
        .text-slate-600, .text-slate-500, .text-slate-800, .text-secondary, .text-primary, p:not(.hero-no-filter), span:not(.hero-no-filter), h1:not(.hero-no-filter), h2:not(.hero-no-filter), h3:not(.hero-no-filter), h4:not(.hero-no-filter), h5:not(.hero-no-filter), h6:not(.hero-no-filter) {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 - var(--contrast-level) * 0.3));
        }

        .hero-no-filter {
            filter: none !important;
        }

        .dark .text-slate-400:not(.hero-no-filter),
        .dark .text-slate-300:not(.hero-no-filter),
        .dark .text-slate-50:not(.hero-no-filter),
        .dark .text-secondary:not(.hero-no-filter),
        .dark .text-white:not(.hero-no-filter),
        .dark p:not(.hero-no-filter),
        .dark span:not(.hero-no-filter),
        .dark h1:not(.hero-no-filter),
        .dark h2:not(.hero-no-filter),
        .dark h3:not(.hero-no-filter),
        .dark h4:not(.hero-no-filter),
        .dark h5:not(.hero-no-filter),
        .dark h6:not(.hero-no-filter) {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 + var(--contrast-level) * 0.4));
        }

        /* High Contrast Mode specific overrides */
        @if(($settings['high_contrast'] ?? '0') == '1')
            .text-slate-600,
            .text-slate-500,
            .text-slate-800,
            .text-secondary,
            .text-primary,
            p,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                color: #000 !important;
            }

            .dark .text-slate-400,
            .dark .text-slate-300,
            .dark .text-slate-50,
            .dark .text-secondary,
            .dark .text-white,
            .dark p,
            .dark h1,
            .dark h2,
            .dark h3,
            .dark h4,
            .dark h5,
            .dark h6 {
                color: #fff !important;
            }

        @endif

        /* Smooth transitions */
        html, body, p, span, h1, h2, h3, h4, h5, h6, a, button {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1), filter 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    <script>
        // Init Theme to prevent FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="bg-[#f6f7f8] dark:bg-[#0f172a] text-[#0d141b] dark:text-slate-50 min-h-screen">
    @include('partials.header')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</body>

</html>