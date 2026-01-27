@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            border-color: #cfdbe7;
            background-color: white;
            border-bottom: 1px solid #cfdbe7;
            padding: 8px !important;
            position: relative;
            z-index: 10;
        }

        .ql-container.ql-snow {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border-color: #cfdbe7;
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            min-height: 80px;
            position: relative;
            z-index: 5;
        }

        .ql-editor {
            min-height: 80px;
            font-size: 14px;
            padding: 12px 15px !important;
            line-height: 1.6;
        }

        /* Fix for Quill dropdowns/pickers */
        .ql-snow .ql-picker-options {
            z-index: 1000 !important;
            background-color: white !important;
            border-color: #cfdbe7 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .dark .ql-toolbar.ql-snow {
            border-color: #1a1a1a;
            background-color: #0b0c11;
        }

        .dark .ql-container.ql-snow {
            border-color: #1a1a1a;
            background-color: #121212;
            color: #f8fafc;
        }

        .dark .ql-editor {
            color: #f8fafc;
        }

        .dark .ql-snow .ql-picker-options {
            background-color: #121212 !important;
            border-color: #1a1a1a !important;
        }

        .section-nav-item {
            color: #475569;
        }

        .section-nav-item:hover {
            background-color: rgba(241, 245, 249, 1);
        }

        .section-nav-item.active {
            background-color: rgba(19, 127, 236, 0.05);
            color: var(--primary-color);
            border: 1px solid rgba(19, 127, 236, 0.2);
        }

        .dark .section-nav-item {
            color: #94a3b8;
        }

        .dark .section-nav-item:hover {
            background-color: rgba(26, 26, 26, 1);
        }

        .dark .section-nav-item.active {
            background-color: rgba(19, 127, 236, 0.1);
            color: #38bdf8;
            border-color: rgba(19, 127, 236, 0.3);
        }
    </style>
@endpush

@section('content')
    <div class="h-[calc(100vh-120px)] -m-8 flex flex-col overflow-hidden bg-[#f0f2f5] dark:bg-black">
        <!-- Site Builder Header -->
        <div
            class="bg-white dark:bg-[#0b0c11] border-b border-slate-200 dark:border-slate-800 px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <nav class="flex items-center text-xs font-bold text-slate-400">
                    <span class="hover:text-primary cursor-pointer">Landing Page</span>
                    <span class="material-symbols-outlined text-xs mx-1">chevron_right</span>
                    <span class="text-slate-800 dark:text-white">Site Builder</span>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('welcome') }}" target="_blank"
                    class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-sm">visibility</span>
                    Live Preview
                </a>
                <div class="w-px h-6 bg-slate-200 dark:border-slate-800 mx-2"></div>
                <button type="button" onclick="location.reload()"
                    class="text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">Discard</button>
                <button type="submit" form="builder-form" id="publish-btn"
                    class="bg-primary text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Publish
                    Changes</button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar: Component Navigation -->
            <aside
                class="w-64 bg-white dark:bg-[#0b0c11] border-r border-slate-200 dark:border-slate-800 flex flex-col shrink-0">
                <div class="p-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Components</h3>
                    <nav class="space-y-1">
                        <button onclick="switchSection('hero')"
                            class="section-nav-item active w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                            data-section="hero">
                            <span class="material-symbols-outlined text-sm">campaign</span>
                            <span class="text-sm font-bold">Hero Section</span>
                        </button>
                        <button onclick="switchSection('rooms')"
                            class="section-nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                            data-section="rooms">
                            <span class="material-symbols-outlined text-sm">bedroom_parent</span>
                            <span class="text-sm font-bold">Rooms & Suites</span>
                        </button>
                        <button onclick="switchSection('cafe')"
                            class="section-nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                            data-section="cafe">
                            <span class="material-symbols-outlined text-sm">local_bar</span>
                            <span class="text-sm font-bold">Andros Cafe</span>
                        </button>
                        <button onclick="switchSection('footer')"
                            class="section-nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                            data-section="footer">
                            <span class="material-symbols-outlined text-sm">bottom_panel_open</span>
                            <span class="text-sm font-bold">Pie de Página (Footer)</span>
                        </button>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                            <button onclick="switchSection('location')"
                                class="section-nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                                data-section="location">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <span class="text-sm font-bold">Ubicación & Mapa</span>
                            </button>

                            <button onclick="switchSection('attractions')"
                                class="section-nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
                                data-section="attractions">
                                <span class="material-symbols-outlined text-sm">travel_explore</span>
                                <span class="text-sm font-bold">Local Attractions</span>
                            </button>

                            <button onclick="switchSection('home_carousel')"
                                class="section-nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all mt-4"
                                data-section="home_carousel">
                                <span class="material-symbols-outlined text-sm">view_carousel</span>
                                <span class="text-sm font-bold">Home Carousel</span>
                            </button>

                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content: Editor View -->
            <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data" id="builder-form"
                class="flex-1 overflow-y-auto px-6 md:px-12 py-10 space-y-10">
                @csrf
                <div id="section-hero" class="builder-section block animate-fade-in">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component: Hero
                        </h2>
                        <p class="text-sm text-slate-500">Manage the first impression and dynamic content of your landing
                            page.</p>
                    </div>

                    <div class="space-y-10">
                        <!-- Media Card -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">image</span>
                                Media Assets
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div
                                    class="relative group aspect-[16/9] rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-black">
                                    <img src="{{ $settings['hero_image'] ?? '/images/branding/hero.png' }}"
                                        id="hero-preview" class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center gap-4">
                                        <button type="button" onclick="openMediaLibrary('hero_image', 'hero-preview')"
                                            class="bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-2xl transition-all">
                                            <span class="material-symbols-outlined text-3xl">grid_view</span>
                                        </button>
                                        <label
                                            class="bg-primary text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-primary/90 transition-all">
                                            Upload New
                                            <input type="file" name="hero_image_file" class="hidden"
                                                onchange="handleNewUpload(this, 'hero_image', 'hero-preview')">
                                        </label>
                                    </div>
                                    <input type="hidden" name="hero_image" id="hero_image_input"
                                        value="{{ $settings['hero_image'] ?? '' }}">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="text-xs font-bold text-slate-800 dark:text-white mb-1">Recommended Size</p>
                                    <p class="text-[11px] text-slate-500 mb-4">1920 x 1080px (Main landing background)</p>
                                    <p class="text-[10px] text-slate-400 italic">Format: JPG, PNG or WEBP (Max 5MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content Card -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">description</span>
                                Content Settings
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Main
                                        Title (ES)</label>
                                    <div id="hero_title_es_editor"
                                        class="rounded-xl border border-slate-100 dark:border-slate-800">
                                        {!! $settings['hero_title_es'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="hero_title_es" id="hero_title_es">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Main
                                        Title (EN)</label>
                                    <div id="hero_title_en_editor"
                                        class="rounded-xl border border-slate-100 dark:border-slate-800">
                                        {!! $settings['hero_title_en'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="hero_title_en" id="hero_title_en">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Subtitle
                                        (ES)</label>
                                    <div id="hero_subtitle_es_editor"
                                        class="rounded-xl border border-slate-100 dark:border-slate-800">
                                        {!! $settings['hero_subtitle_es'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="hero_subtitle_es" id="hero_subtitle_es">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Subtitle
                                        (EN)</label>
                                    <div id="hero_subtitle_en_editor"
                                        class="rounded-xl border border-slate-100 dark:border-slate-800">
                                        {!! $settings['hero_subtitle_en'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="hero_subtitle_en" id="hero_subtitle_en">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="section-rooms" class="builder-section hidden">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component: Rooms
                        </h2>
                        <p class="text-sm text-slate-500">Manage the introductory text for the rooms and suites section.</p>
                    </div>

                    <div class="space-y-10">
                        <!-- Content Card -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">description</span>
                                Rooms Section Content
                            </h3>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Badge
                                            (ES)</label>
                                        <div id="rooms_badge_es_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['rooms_badge_es'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="rooms_badge_es" id="rooms_badge_es">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Badge
                                            (EN)</label>
                                        <div id="rooms_badge_en_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['rooms_badge_en'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="rooms_badge_en" id="rooms_badge_en">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Title
                                            (ES)</label>
                                        <div id="rooms_title_es_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['rooms_title_es'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="rooms_title_es" id="rooms_title_es">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Title
                                            (EN)</label>
                                        <div id="rooms_title_en_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['rooms_title_en'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="rooms_title_en" id="rooms_title_en">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Description
                                        (ES)</label>
                                    <div id="rooms_description_es_editor"
                                        class="rounded-xl border border-slate-100 dark:border-slate-800">
                                        {!! $settings['rooms_description_es'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="rooms_description_es" id="rooms_description_es">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Description
                                        (EN)</label>
                                    <div id="rooms_description_en_editor"
                                        class="rounded-xl border border-slate-100 dark:border-slate-800">
                                        {!! $settings['rooms_description_en'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="rooms_description_en" id="rooms_description_en">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="section-cafe" class="builder-section hidden">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component: Andros
                            Cafe</h2>
                        <p class="text-sm text-slate-500">Manage visual styles and content for the culinary section.</p>
                    </div>

                    <div class="space-y-10">
                        <!-- Media Card -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">image</span>
                                Media Assets
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div
                                    class="relative group aspect-video rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-black">
                                    <img src="{{ $settings['cafe_image'] ?? '/images/gallery/bar.png' }}" id="cafe-preview"
                                        class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center gap-4">
                                        <button type="button" onclick="openMediaLibrary('cafe_image', 'cafe-preview')"
                                            class="bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-2xl transition-all">
                                            <span class="material-symbols-outlined text-3xl">grid_view</span>
                                        </button>
                                        <label
                                            class="bg-primary text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-primary/90 transition-all">
                                            Upload New
                                            <input type="file" name="cafe_image_file" class="hidden"
                                                onchange="handleNewUpload(this, 'cafe_image', 'cafe-preview')">
                                        </label>
                                    </div>
                                    <input type="hidden" name="cafe_image" id="cafe_image_input"
                                        value="{{ $settings['cafe_image'] ?? '' }}">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="text-xs font-bold text-slate-800 dark:text-white mb-1">Recommended Size</p>
                                    <p class="text-[11px] text-slate-500 mb-4">1920 x 1080px (Section Background/Photo)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content Card -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">description</span>
                                Content Settings
                            </h3>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Image
                                            Badge (ES)</label>
                                        <input type="text" name="cafe_image_badge_es"
                                            value="{{ $settings['cafe_image_badge_es'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50"
                                            placeholder="Experiencia Única">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Image
                                            Badge (EN)</label>
                                        <input type="text" name="cafe_image_badge_en"
                                            value="{{ $settings['cafe_image_badge_en'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50"
                                            placeholder="Unique Experience">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Text
                                            Color</label>
                                        <div class="flex items-center gap-3">
                                            <input type="color" name="cafe_text_color"
                                                value="{{ $settings['cafe_text_color'] ?? '#ffffff' }}"
                                                class="w-12 h-12 bg-transparent border-none cursor-pointer p-0">
                                            <input type="text" value="{{ $settings['cafe_text_color'] ?? '#ffffff' }}"
                                                class="flex-1 bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/50"
                                                readonly placeholder="#ffffff">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Overlay
                                            Color</label>
                                        <div class="flex items-center gap-3">
                                            <input type="color" name="cafe_overlay_color"
                                                value="{{ $settings['cafe_overlay_color'] ?? '#000000' }}"
                                                class="w-12 h-12 bg-transparent border-none cursor-pointer p-0">
                                            <input type="text" value="{{ $settings['cafe_overlay_color'] ?? '#000000' }}"
                                                class="flex-1 bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-primary/50"
                                                readonly placeholder="#000000">
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Overlay
                                            Opacity ({{ $settings['cafe_overlay_opacity'] ?? 80 }}%)</label>
                                        <div class="flex items-center gap-4 py-3">
                                            <input type="range" name="cafe_overlay_opacity" min="0" max="100" step="5"
                                                value="{{ $settings['cafe_overlay_opacity'] ?? 80 }}"
                                                class="w-full accent-primary h-2 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer"
                                                oninput="this.closest('div').previousElementSibling.innerText = 'Overlay Opacity (' + this.value + '%)'">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Image
                                            Title (ES)</label>
                                        <input type="text" name="cafe_image_title_es"
                                            value="{{ $settings['cafe_image_title_es'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50"
                                            placeholder="The Palm Lounge">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Image
                                            Title (EN)</label>
                                        <input type="text" name="cafe_image_title_en"
                                            value="{{ $settings['cafe_image_title_en'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50"
                                            placeholder="The Palm Lounge">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Section
                                        Headline (ES)</label>
                                    <input type="text" name="cafe_title_es" value="{{ $settings['cafe_title_es'] ?? '' }}"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Section
                                        Headline (EN)</label>
                                    <input type="text" name="cafe_title_en" value="{{ $settings['cafe_title_en'] ?? '' }}"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Body
                                        Description (ES)</label>
                                    <div id="cafe_description_es_editor"
                                        class="rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800">
                                        {!! $settings['cafe_description_es'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="cafe_description_es" id="cafe_description_es">
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Body
                                        Description (EN)</label>
                                    <div id="cafe_description_en_editor"
                                        class="rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800">
                                        {!! $settings['cafe_description_en'] ?? '' !!}
                                    </div>
                                    <input type="hidden" name="cafe_description_en" id="cafe_description_en">
                                </div>
                            </div>
                        </div>

                        <!-- Features Card -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">view_carousel</span>
                                Feature Cards
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <!-- Feature 1 -->
                                <div
                                    class="p-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-[#0b0c11]/30 flex flex-col gap-6">
                                    <div class="flex items-center gap-4 shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                                            <span
                                                class="material-symbols-outlined text-primary text-2xl">{{ $settings['cafe_feature1_icon'] ?? 'coffee' }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <label
                                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Icon
                                                ID (Material Symbol)</label>
                                            <input type="text" name="cafe_feature1_icon"
                                                value="{{ $settings['cafe_feature1_icon'] ?? 'coffee' }}"
                                                class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                                placeholder="e.g. coffee">
                                        </div>
                                    </div>
                                    <div class="flex-1 flex flex-col gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Card
                                                    Title (ES)</label>
                                                <input type="text" name="cafe_feature1_title_es"
                                                    value="{{ $settings['cafe_feature1_title_es'] ?? '' }}"
                                                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                                    placeholder="Título (ES)">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Card
                                                    Title (EN)</label>
                                                <input type="text" name="cafe_feature1_title_en"
                                                    value="{{ $settings['cafe_feature1_title_en'] ?? '' }}"
                                                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                                    placeholder="Title (EN)">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Description
                                                    (ES)</label>
                                                <div id="cafe_feature1_desc_es_editor"
                                                    class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                                                    {!! $settings['cafe_feature1_desc_es'] ?? '' !!}
                                                </div>
                                                <input type="hidden" name="cafe_feature1_desc_es"
                                                    id="cafe_feature1_desc_es">
                                            </div>
                                            <div class="flex flex-col">
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Description
                                                    (EN)</label>
                                                <div id="cafe_feature1_desc_en_editor"
                                                    class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                                                    {!! $settings['cafe_feature1_desc_en'] ?? '' !!}
                                                </div>
                                                <input type="hidden" name="cafe_feature1_desc_en"
                                                    id="cafe_feature1_desc_en">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Feature 2 -->
                                <div
                                    class="p-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-[#0b0c11]/30 flex flex-col gap-6">
                                    <div class="flex items-center gap-4 shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                                            <span
                                                class="material-symbols-outlined text-primary text-2xl">{{ $settings['cafe_feature2_icon'] ?? 'restaurant' }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <label
                                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Icon
                                                ID (Material Symbol)</label>
                                            <input type="text" name="cafe_feature2_icon"
                                                value="{{ $settings['cafe_feature2_icon'] ?? 'restaurant' }}"
                                                class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                                placeholder="e.g. local_bar">
                                        </div>
                                    </div>
                                    <div class="flex-1 flex flex-col gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Card
                                                    Title (ES)</label>
                                                <input type="text" name="cafe_feature2_title_es"
                                                    value="{{ $settings['cafe_feature2_title_es'] ?? '' }}"
                                                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                                    placeholder="Título (ES)">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Card
                                                    Title (EN)</label>
                                                <input type="text" name="cafe_feature2_title_en"
                                                    value="{{ $settings['cafe_feature2_title_en'] ?? '' }}"
                                                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-primary/50"
                                                    placeholder="Title (EN)">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Description
                                                    (ES)</label>
                                                <div id="cafe_feature2_desc_es_editor"
                                                    class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                                                    {!! $settings['cafe_feature2_desc_es'] ?? '' !!}
                                                </div>
                                                <input type="hidden" name="cafe_feature2_desc_es"
                                                    id="cafe_feature2_desc_es">
                                            </div>
                                            <div class="flex flex-col">
                                                <label
                                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Description
                                                    (EN)</label>
                                                <div id="cafe_feature2_desc_en_editor"
                                                    class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                                                    {!! $settings['cafe_feature2_desc_en'] ?? '' !!}
                                                </div>
                                                <input type="hidden" name="cafe_feature2_desc_en"
                                                    id="cafe_feature2_desc_en">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Home Carousel Section -->
                <div id="section-home_carousel" class="builder-section hidden">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component: Home
                            Carousel</h2>
                        <p class="text-sm text-slate-500">Customize the headers and badges for the home gallery showcase.
                        </p>
                    </div>

                    <div
                        class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">description</span>
                            Carousel Headers
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Badge
                                    (ES)</label>
                                <input type="text" name="carousel_badge_es"
                                    value="{{ $settings['carousel_badge_es'] ?? '' }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Badge
                                    (EN)</label>
                                <input type="text" name="carousel_badge_en"
                                    value="{{ $settings['carousel_badge_en'] ?? '' }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Title
                                    (ES)</label>
                                <input type="text" name="carousel_title_es"
                                    value="{{ $settings['carousel_title_es'] ?? '' }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Title
                                    (EN)</label>
                                <input type="text" name="carousel_title_en"
                                    value="{{ $settings['carousel_title_en'] ?? '' }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                            </div>
                        </div>

                        <!-- Gallery Photos Integration -->
                        <div class="pt-10 border-t border-slate-100 dark:border-slate-800 space-y-8">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <h3
                                    class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">view_carousel</span>
                                    Carousel Images (Active)
                                </h3>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="syncGalleryManual(this)"
                                        class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all border border-slate-200 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-sm">sync</span>
                                        Sincronizar
                                    </button>
                                    <button type="button" onclick="openCarouselGalleryModal()"
                                        class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">photo_library</span>
                                        Escoger de Galería
                                    </button>
                                    <button type="button" onclick="document.getElementById('carousel-upload-input').click()"
                                        class="cursor-pointer bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">upload</span>
                                        Subir Nueva
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @forelse($carousel_images as $item)
                                    <div
                                        class="relative aspect-square group rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm">
                                        <img src="{{ $item->image_path }}" class="w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2">
                                            <button type="button" onclick="submitToggleCarousel({{ $item->id }})"
                                                class="bg-white/90 text-amber-600 p-2.5 rounded-xl hover:bg-white transition-all transform scale-90 group-hover:scale-100"
                                                title="Quitar del carrusel">
                                                <span class="material-symbols-outlined text-sm font-bold">visibility_off</span>
                                            </button>
                                            <button type="button" onclick="submitDeleteGallery({{ $item->id }})"
                                                class="bg-white/90 text-red-600 p-2.5 rounded-xl hover:bg-white transition-all transform scale-90 group-hover:scale-100"
                                                title="Eliminar permanentemente">
                                                <span class="material-symbols-outlined text-sm font-bold">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="col-span-full py-12 flex flex-col items-center justify-center text-slate-400 border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-3xl">
                                        <span class="material-symbols-outlined text-4xl mb-4">image_not_supported</span>
                                        <p class="text-[10px] font-bold uppercase tracking-widest">No hay imágenes en el
                                            carrusel</p>
                                        <p class="text-[9px] mt-1 uppercase opacity-60">Sube fotos o elígelas de la galería</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Location Section -->
                <div id="section-location" class="builder-section hidden pb-20">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component:
                            Location</h2>
                        <p class="text-sm text-slate-500">Manage maps, addresses and local features.</p>
                    </div>
                    <div class="space-y-10">
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 pb-16 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8 h-auto">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">map</span>
                                Map & Connectivity
                            </h3>
                            <div class="space-y-6">
                                <!-- Address & Maps -->
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Hotel
                                            Address</label>
                                        <input type="text" name="hotel_address"
                                            value="{{ $settings['hotel_address'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                            placeholder="Hotel full address">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2">Google
                                            Maps Embed URL (SRC)</label>
                                        <input type="text" name="google_maps_iframe"
                                            value="{{ $settings['google_maps_iframe'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                            placeholder="https://www.google.com/maps/embed...">
                                    </div>
                                </div>

                                <!-- Badge & Titles -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Badge ES -->
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Badge
                                            Superior (ES)</label>
                                        <input type="text" name="location_badge_es"
                                            value="{{ $settings['location_badge_es'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                            placeholder="Donde estamos ubicados">
                                    </div>

                                    <!-- Badge EN -->
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Badge
                                            Superior (EN)</label>
                                        <input type="text" name="location_badge_en"
                                            value="{{ $settings['location_badge_en'] ?? '' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50"
                                            placeholder="Where we are located">
                                    </div>
                                </div>

                                <!-- Titles & Descriptions -->
                                <div class="grid grid-cols-1 gap-8">
                                    <!-- Title ES -->
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Title
                                            (ES)</label>
                                        <div id="location_title_es_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['location_title_es'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="location_title_es" id="location_title_es">
                                    </div>

                                    <!-- Title EN -->
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Title
                                            (EN)</label>
                                        <div id="location_title_en_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['location_title_en'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="location_title_en" id="location_title_en">
                                    </div>

                                    <!-- Description ES -->
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase mb-2">Description
                                            (ES)</label>
                                        <div id="location_description_es_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['location_description_es'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="location_description_es" id="location_description_es">
                                    </div>

                                    <!-- Description EN -->
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 uppercase mb-2">Description
                                            (EN)</label>
                                        <div id="location_description_en_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800">
                                            {!! $settings['location_description_en'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="location_description_en" id="location_description_en">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attractions Section -->
                <div id="section-attractions" class="builder-section hidden">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component:
                            Local Attractions</h2>
                        <p class="text-sm text-slate-500">Manage points of interest around your hotel.</p>
                    </div>
                    <div
                        class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-8">
                        <div
                            class="flex justify-between items-center text-xs font-black text-slate-400 uppercase tracking-widest">
                            <span class="flex items-center gap-2"><span
                                    class="material-symbols-outlined text-sm">explore</span>Attractions List</span>
                            <button type="button"
                                onclick="document.getElementById('attraction-form-modal').classList.toggle('hidden')"
                                class="bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all">+
                                Add Attraction</button>
                        </div>

                        <!-- Section Headers -->
                        <div class="space-y-6">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                {{ __('Títulos de la Sección') }}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Badge
                                        (ES)</label>
                                    <input type="text" name="attractions_badge_es"
                                        value="{{ $settings['attractions_badge_es'] ?? 'EXPLORE PANAMA' }}"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Badge
                                        (EN)</label>
                                    <input type="text" name="attractions_badge_en"
                                        value="{{ $settings['attractions_badge_en'] ?? 'EXPLORE PANAMA' }}"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Título
                                        (ES)</label>
                                    <input type="text" name="attractions_title_es"
                                        value="{{ $settings['attractions_title_es'] ?? 'Local Attractions' }}"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Title
                                        (EN)</label>
                                    <input type="text" name="attractions_title_en"
                                        value="{{ $settings['attractions_title_en'] ?? 'Local Attractions' }}"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100 dark:border-slate-800">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($attractions as $attraction)
                                <div
                                    class="flex gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 group relative">
                                    <div
                                        class="w-20 h-20 rounded-xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700">
                                        <img src="{{ $attraction->image_path }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col justify-center overflow-hidden">
                                        <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate">
                                            {{ $attraction->title_es }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">
                                            {{ strip_tags($attraction->description_es) }}
                                        </p>
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-all z-20">
                                        <button type="button"
                                            onclick='editAttraction({
                                                                                                                                                                                                                                                                                            id: "{{ $attraction->id }}",
                                                                                                                                                                                                                                                                                            title_es: "{{ addslashes($attraction->title_es) }}",
                                                                                                                                                                                                                                                                                            title_en: "{{ addslashes($attraction->title_en) }}",
                                                                                                                                                                                                                                                                                            description_es: "{{ addslashes($attraction->description_es) }}",
                                                                                                                                                                                                                                                                                            description_en: "{{ addslashes($attraction->description_en) }}",
                                                                                                                                                                                                                                                                                            image_path: "{{ $attraction->image_path }}"
                                                                                                                                                                                                                                                                                        })'
                                            class="text-blue-500 hover:text-blue-700 p-2 bg-white dark:bg-[#0b0c11] rounded-full shadow-sm">
                                            <span class="material-symbols-outlined text-sm font-bold">edit</span>
                                        </button>
                                        <button type="button" onclick="confirmDeleteAttraction('{{ $attraction->id }}', event)"
                                            class="text-red-500 hover:text-red-700 p-2 bg-white dark:bg-[#0b0c11] rounded-full shadow-sm">
                                            <span class="material-symbols-outlined text-sm font-bold">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer Section -->
                <div id="section-footer" class="builder-section hidden pb-20">
                    <div class="flex flex-col gap-2 mb-10">
                        <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">Edit Component: Footer
                        </h2>
                        <p class="text-sm text-slate-500">Manage your brand identity, social links and legal information.
                        </p>
                    </div>

                    <div class="space-y-10">
                        <!-- Brand Description -->
                        <div
                            class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">description</span>
                                Brand Description
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Description
                                        (ES)</label>
                                    <textarea name="footer_description_es" rows="3"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm">{{ $settings['footer_description_es'] ?? 'Redefiniendo el viaje de lujo desde 1994. Experimente una hospitalidad inigualable y un confort excepcional.' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Description
                                        (EN)</label>
                                    <textarea name="footer_description_en" rows="3"
                                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm">{{ $settings['footer_description_en'] ?? 'Redefining the luxury travel since 1994. Experience unparalleled hospitality and exceptional comfort.' }}</textarea>
                                </div>
                            </div>

                            <hr class="border-slate-100 dark:border-slate-800">

                            <!-- Policies Editor -->
                            <div class="space-y-6 overflow-hidden">
                                <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ __('Políticas del Hotel') }}
                                </h4>
                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Políticas
                                            (ES)</label>
                                        <div id="footer_policies_es_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800 h-80 bg-white dark:bg-black overflow-hidden">
                                            {!! $settings['footer_policies_es'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="footer_policies_es" id="footer_policies_es">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Policies
                                            (EN)</label>
                                        <div id="footer_policies_en_editor"
                                            class="rounded-xl border border-slate-100 dark:border-slate-800 h-80 bg-white dark:bg-black overflow-hidden">
                                            {!! $settings['footer_policies_en'] ?? '' !!}
                                        </div>
                                        <input type="hidden" name="footer_policies_en" id="footer_policies_en">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div <!-- Social Media Links (Dynamic) -->
                            <div
                                class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                                <div class="flex justify-between items-center">
                                    <h3
                                        class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">share</span>
                                        Social Media Links
                                    </h3>
                                    <button type="button" onclick="addSocialLink()"
                                        class="bg-primary/10 text-primary hover:bg-primary/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                        + Add Social Link
                                    </button>
                                </div>

                                <div id="social-links-container" class="space-y-4">
                                    <!-- Dynamic rows will be injected here -->
                                </div>

                                <input type="hidden" name="footer_socials_json" id="footer_socials_json">
                            </div>

                            <!-- Contact & Copyright -->
                            <div
                                class="bg-white dark:bg-[#0b0c11] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                                <h3
                                    class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">contact_support</span>
                                    Contact & Rights
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Contact
                                            Description
                                            (ES)</label>
                                        <input type="text" name="footer_contact_description_es"
                                            value="{{ $settings['footer_contact_description_es'] ?? '¿Tienes alguna duda o requerimiento especial? Estamos aquí para ayudarte.' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Contact
                                            Description
                                            (EN)</label>
                                        <input type="text" name="footer_contact_description_en"
                                            value="{{ $settings['footer_contact_description_en'] ?? 'Do you have any questions or special requirements? We are here to help.' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Copyright
                                            Text
                                            (ES)</label>
                                        <input type="text" name="footer_copyright_es"
                                            value="{{ $settings['footer_copyright_es'] ?? 'Todos los derechos reservados.' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Copyright
                                            Text
                                            (EN)</label>
                                        <input type="text" name="footer_copyright_en"
                                            value="{{ $settings['footer_copyright_en'] ?? 'All rights reserved.' }}"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>

            {{-- Hidden Delete Forms will be at the end of the document --}}

        </div>
    </div>

    <style>
        .section-nav-item {
            color: #4c739a;
        }

        .section-nav-item:hover {
            background-color: #f8fafc;
            color: var(--primary-color);
        }

        .section-nav-item.active {
            background-color: rgba(19, 127, 236, 0.1);
            color: var(--primary-color);
        }

        .dark .section-nav-item:hover {
            background-color: #1e293b;
        }

        .dark .section-nav-item.active {
            background-color: rgba(19, 127, 236, 0.2);
        }

        .builder-section {
            display: none;
        }

        .builder-section.block {
            display: block;
        }

        /* Quill Toolbar Fix */
        .ql-toolbar.ql-snow {
            display: flex;
            flex-wrap: wrap;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            background: #fff;
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .ql-container.ql-snow {
            border: none !important;
            min-height: 100px;
        }

        .dark .ql-toolbar.ql-snow {
            background: #1e293b;
            border-bottom: 1px solid #334155 !important;
        }
    </style>


    <!-- Hidden Forms & Modals -->
    <div id="hidden-forms" class="hidden">
        {{-- Formulario para subir imágenes al carrusel sin afectar al builder-form --}}
        <form id="carousel-upload-form" action="{{ route('admin.gallery.store') }}?show_in_carousel=1" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="file" name="gallery_images[]" id="carousel-upload-input" multiple onchange="this.form.submit()">
        </form>

        {{-- Formulario para alternar visibilidad en carrusel --}}
        <form id="toggle-carousel-form" method="POST">
            @csrf
        </form>

        {{-- Formularios de eliminación --}}
        @foreach($gallery as $item)
            <form id="delete-gallery-{{ $item->id }}" action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST">
                @csrf @method('DELETE')
            </form>
        @endforeach

        @foreach($attractions as $attraction)
            <form id="delete-attraction-{{ $attraction->id }}"
                action="{{ route('admin.attractions.destroy', $attraction->id) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>

    <!-- Media Library Modal -->
    <div id="media-library-modal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeMediaLibrary()"></div>
        <div
            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl max-h-[80vh] bg-white dark:bg-[#0b0c11] rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden">
            <div
                class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                <div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Media Library
                    </h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Select an image from your
                        global gallery</p>
                </div>
                <button onclick="closeMediaLibrary()"
                    class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="media-library-grid">
                    @foreach($gallery as $item)
                        <div class="relative aspect-square group rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm cursor-pointer hover:ring-4 hover:ring-primary/40 transition-all"
                            onclick="selectImageFromLibrary('{{ $item->image_path }}')">
                            <img src="{{ $item->image_path }}" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-3xl">check_circle</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="p-8 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <p class="text-xs text-slate-400 font-bold italic">Tip: You can upload more images from the "Global Gallery"
                    section.</p>
                <button onclick="closeMediaLibrary()"
                    class="px-8 py-3 bg-slate-800 dark:bg-white dark:text-slate-900 text-white rounded-xl font-bold text-sm">Cancel</button>
            </div>
        </div>
    </div>

    <div id="attraction-form-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
            onclick="document.getElementById('attraction-form-modal').classList.add('hidden')"></div>
        <div
            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-8 bg-white dark:bg-[#0b0c11] rounded-3xl shadow-2xl">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6" id="attraction-modal-title">New Local
                Attraction</h3>
            <form id="attraction-form" action="{{ route('admin.attractions.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div id="attraction-method-container"></div>
                <input type="hidden" name="id" id="attraction-id-field">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="title_es" placeholder="Título (ES)" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-2.5 text-sm">
                    <input type="text" name="title_en" placeholder="Title (EN)" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Description (ES)</label>
                    <div id="attraction_description_es_editor"
                        class="rounded-xl border border-slate-100 dark:border-slate-800 h-24"></div>
                    <input type="hidden" name="description_es" id="attraction_description_es">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Description (EN)</label>
                    <div id="attraction_description_en_editor"
                        class="rounded-xl border border-slate-100 dark:border-slate-800 h-24"></div>
                    <input type="hidden" name="description_en" id="attraction_description_en">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase mb-2">Image</label>
                    <div
                        class="relative group aspect-video rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-black mb-2">
                        <img src="/images/branding/placeholder.png" id="attraction-preview"
                            class="w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center gap-2">
                            <button type="button" onclick="openMediaLibrary('attraction_image', 'attraction-preview')"
                                class="bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-2 rounded-xl transition-all">
                                <span class="material-symbols-outlined text-xl">grid_view</span>
                            </button>
                            <label
                                class="bg-primary text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest cursor-pointer">
                                Upload
                                <input type="file" name="image_file" class="hidden"
                                    onchange="handleNewUpload(this, 'attraction_image', 'attraction-preview')">
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="image_path" id="attraction_image_input">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('attraction-form-modal').classList.add('hidden')"
                        class="flex-1 px-6 py-3 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">Cancel</button>
                    <button type="submit"
                        class="flex-1 bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Save
                        Attraction</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteConfirmation()"></div>
        <div
            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm p-8 bg-white dark:bg-[#0b0c11] rounded-3xl shadow-2xl text-center">
            <div
                class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">warning</span>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2">¿Estás seguro?</h3>
            <p class="text-sm text-slate-500 mb-6">Esta acción no se puede deshacer. El atractivo turístico será eliminado
                permanentemente.</p>
            <div class="flex gap-4">
                <button type="button" onclick="closeDeleteConfirmation()"
                    class="flex-1 px-6 py-3 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">Cancelar</button>
                <button type="button" id="confirm-delete-btn"
                    class="flex-1 bg-red-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-red-200 dark:shadow-none hover:bg-red-700 transition-all">Eliminar</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const quillOptions = {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'font': [] }],
                            [{ 'header': [1, 2, 3, false] }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'color': [] }, { 'background': [] }],
                            ['clean']
                        ]
                    },
                    placeholder: 'Escribe aquí...'
                };

                const editorsData = [
                    'hero_title_es', 'hero_title_en',
                    'hero_subtitle_es', 'hero_subtitle_en',
                    'rooms_badge_es', 'rooms_badge_en',
                    'rooms_title_es', 'rooms_title_en',
                    'rooms_description_es', 'rooms_description_en',
                    'cafe_description_es', 'cafe_description_en',
                    'cafe_feature1_desc_es', 'cafe_feature1_desc_en',
                    'cafe_feature2_desc_es', 'cafe_feature2_desc_en',
                    'location_title_es', 'location_title_en',
                    'location_description_es', 'location_description_en',
                    'footer_policies_es', 'footer_policies_en'
                ];

                const quillInstances = {};

                editorsData.forEach(id => {
                    const container = document.getElementById(id + '_editor');
                    if (container) {
                        quillInstances[id] = new Quill(container, quillOptions);
                    }
                });

                // Attraction editors in modal
                const attrDescEs = new Quill('#attraction_description_es_editor', quillOptions);
                const attrDescEn = new Quill('#attraction_description_en_editor', quillOptions);

                const builderForm = document.getElementById('builder-form');
                if (builderForm) {
                    builderForm.addEventListener('submit', function (e) {
                        editorsData.forEach(id => {
                            const hiddenInput = document.getElementById(id);
                            if (quillInstances[id] && hiddenInput) {
                                hiddenInput.value = quillInstances[id].root.innerHTML;
                            }
                        });
                        syncSocialsJson();
                    });
                }

                // Handle attraction submit
                const attrForm = document.getElementById('attraction-form');
                if (attrForm) {
                    attrForm.onsubmit = function () {
                        document.getElementById('attraction_description_es').value = attrDescEs.root.innerHTML;
                        document.getElementById('attraction_description_en').value = attrDescEn.root.innerHTML;
                    };
                }

                window.editAttraction = function (data) {
                    const modal = document.getElementById('attraction-form-modal');
                    const form = document.getElementById('attraction-form');
                    const title = document.getElementById('attraction-modal-title');
                    const methodContainer = document.getElementById('attraction-method-container');

                    // Configurar el formulario para actualización
                    title.innerText = 'Edit Local Attraction';
                    form.action = `/admin/attractions/${data.id}`;
                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                    // Poblar campos
                    document.getElementById('attraction-id-field').value = data.id;
                    form.querySelector('[name="title_es"]').value = data.title_es;
                    form.querySelector('[name="title_en"]').value = data.title_en;

                    // Poblar Quill editors
                    attrDescEs.root.innerHTML = data.description_es;
                    attrDescEn.root.innerHTML = data.description_en;

                    // Previsualización de imagen
                    document.getElementById('attraction-preview').src = data.image_path;
                    document.getElementById('attraction_image_input').value = data.image_path;

                    // Resetear input de archivo
                    form.querySelector('[name="image_file"]').value = '';

                    // Mostrar modal
                    modal.classList.remove('hidden');
                };

                window.openAddAttractionModal = function () {
                    const modal = document.getElementById('attraction-form-modal');
                    const form = document.getElementById('attraction-form');
                    const title = document.getElementById('attraction-modal-title');
                    const methodContainer = document.getElementById('attraction-method-container');

                    // Resetear el formulario para creación
                    title.innerText = 'New Local Attraction';
                    form.action = "{{ route('admin.attractions.store') }}";
                    methodContainer.innerHTML = '';
                    form.reset();
                    document.getElementById('attraction-id-field').value = '';

                    // Resetear Quill
                    attrDescEs.root.innerHTML = '';
                    attrDescEn.root.innerHTML = '';

                    // Resetear previsualización
                    document.getElementById('attraction-preview').src = "/images/branding/placeholder.png";
                    document.getElementById('attraction_image_input').value = '';

                    modal.classList.remove('hidden');
                };

                // Actualizar el botón de "Añadir Atractivo" para usar la nueva función
                const addAttrBtn = document.querySelector('button[onclick*="attraction-form-modal"]');
                if (addAttrBtn) {
                    addAttrBtn.setAttribute('onclick', 'openAddAttractionModal()');
                }

                let attractionIdToDelete = null;

                window.confirmDeleteAttraction = function (id, event) {
                    if (event) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    attractionIdToDelete = id;
                    const modal = document.getElementById('delete-confirmation-modal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };

                window.closeDeleteConfirmation = function () {
                    const modal = document.getElementById('delete-confirmation-modal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    attractionIdToDelete = null;
                };

                const confirmDelBtn = document.getElementById('confirm-delete-btn');
                if (confirmDelBtn) {
                    confirmDelBtn.onclick = function () {
                        if (attractionIdToDelete) {
                            const form = document.getElementById('delete-attraction-' + attractionIdToDelete);
                            if (form) {
                                form.submit();
                            } else {
                                console.error('Delete form not found for ID:', attractionIdToDelete);
                            }
                        }
                    };
                }

                window.submitToggleCarousel = function (id) {
                    const form = document.getElementById('toggle-carousel-form');
                    form.action = `/admin/gallery/${id}/toggle-carousel`;
                    form.submit();
                };

                window.submitDeleteGallery = function (id) {
                    if (confirm('¿Eliminar esta imagen por completo?')) {
                        const form = document.getElementById('delete-gallery-' + id);
                        if (form) form.submit();
                    }
                };

                renderSocialLinks();
            });

            function previewImage(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById(previewId).src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            let activeImageInputId = '';
            let activePreviewId = '';

            window.openMediaLibrary = function (inputId, previewId) {
                activeImageInputId = inputId;
                activePreviewId = previewId;
                document.getElementById('media-library-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            window.closeMediaLibrary = function () {
                document.getElementById('media-library-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            window.selectImageFromLibrary = function (path) {
                const targetId = activeImageInputId === 'attraction_image' ? 'attraction_image_input' : activeImageInputId + '_input';
                const input = document.getElementById(targetId);
                const preview = document.getElementById(activePreviewId);

                if (input) input.value = path;
                if (preview) preview.src = path;

                // Limpiar el input de archivo para evitar que el servidor lo procese
                const fileInputName = activeImageInputId === 'attraction_image' ? 'image_file' : activeImageInputId + '_file';
                const fileInput = document.querySelector(`input[name="${fileInputName}"]`);
                if (fileInput) fileInput.value = '';

                closeMediaLibrary();
            }

            window.handleNewUpload = function (input, targetInputId, previewId) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById(previewId).src = e.target.result;
                        const targetId = targetInputId === 'attraction_image' ? 'attraction_image_input' : targetInputId + '_input';
                        const hiddenInput = document.getElementById(targetId);
                        if (hiddenInput) hiddenInput.value = '';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            window.switchSection = function (sectionId) {
                // Hide all sections
                document.querySelectorAll('.builder-section').forEach(s => {
                    s.classList.remove('block');
                    s.classList.add('hidden');
                });

                // Deactivate all nav items
                document.querySelectorAll('.section-nav-item').forEach(i => i.classList.remove('active'));

                const targetSection = document.getElementById('section-' + sectionId);
                const targetNav = document.querySelector(`[data-section="${sectionId}"]`);

                // Show target section
                if (targetSection) {
                    targetSection.classList.remove('hidden');
                    targetSection.classList.add('block');
                }
                if (targetNav) targetNav.classList.add('active');
            };

            // Social Links Logic
            let socialLinks = {!! $settings['footer_socials_json'] ?? '[]' !!};
            if (!Array.isArray(socialLinks) || socialLinks.length === 0) {
                if (typeof socialLinks === 'string') {
                    try { socialLinks = JSON.parse(socialLinks); } catch (e) { socialLinks = []; }
                }
                if (socialLinks.length === 0) {
                    socialLinks = [
                        { platform: 'instagram', url: '{{ $settings["footer_instagram"] ?? "#" }}', active: true },
                        { platform: 'facebook', url: '#', active: true },
                        { platform: 'linkedin', url: '{{ $settings["footer_linkedin"] ?? "#" }}', active: true }
                    ];
                }
            }

            function renderSocialLinks() {
                const container = document.getElementById('social-links-container');
                if (!container) return;
                container.innerHTML = '';
                socialLinks.forEach((link, index) => {
                    const row = document.createElement('div');
                    row.className = 'flex flex-col md:flex-row items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800';
                    row.innerHTML = `
                                                                                                                                                                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Platform</label>
                                                                                                                                                                        <select onchange="updateSocialLink(${index}, 'platform', this.value)" class="w-full bg-white dark:bg-[#0b0c11] border-none rounded-xl px-4 py-2 text-sm">
                                                                                                                                                                            <option value="instagram" ${link.platform === 'instagram' ? 'selected' : ''}>Instagram</option>
                                                                                                                                                                            <option value="facebook" ${link.platform === 'facebook' ? 'selected' : ''}>Facebook</option>
                                                                                                                                                                            <option value="linkedin" ${link.platform === 'linkedin' ? 'selected' : ''}>LinkedIn</option>
                                                                                                                                                                            <option value="twitter" ${link.platform === 'twitter' ? 'selected' : ''}>Twitter / X</option>
                                                                                                                                                                            <option value="tiktok" ${link.platform === 'tiktok' ? 'selected' : ''}>TikTok</option>
                                                                                                                                                                            <option value="youtube" ${link.platform === 'youtube' ? 'selected' : ''}>YouTube</option>
                                                                                                                                                                            <option value="whatsapp" ${link.platform === 'whatsapp' ? 'selected' : ''}>WhatsApp</option>
                                                                                                                                                                        </select>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div>
                                                                                                                                                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">URL</label>
                                                                                                                                                                        <input type="text" value="${link.url}" placeholder="https://..." onchange="updateSocialLink(${index}, 'url', this.value)" class="w-full bg-white dark:bg-[#0b0c11] border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20">
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="flex items-center gap-4">
                                                                                                                                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                                                                                                                                        <div class="relative">
                                                                                                                                                                            <input type="checkbox" ${link.active ? 'checked' : ''} onchange="updateSocialLink(${index}, 'active', this.checked)" class="sr-only peer">
                                                                                                                                                                            <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                                                                                                                                                        </div>
                                                                                                                                                                        <span class="text-[10px] font-black text-slate-400 uppercase group-hover:text-primary transition-colors">Active</span>
                                                                                                                                                                    </label>
                                                                                                                                                                    <button type="button" onclick="removeSocialLink(${index})" class="text-slate-400 hover:text-red-500 transition-colors">
                                                                                                                                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                                                                                                                                    </button                                                                                                                                                                                                             >
                                                                                                                                                                </div>
                                                                                                                                                            `;
                    container.appendChild(row);
                });
                syncSocialsJson();
            }

            window.addSocialLink = function () { socialLinks.push({ platform: 'facebook', url: '#', active: true }); renderSocialLinks(); };
            window.removeSocialLink = function (index) { socialLinks.splice(index, 1); renderSocialLinks(); };
            window.updateSocialLink = function (index, field, value) { socialLinks[index][field] = value; syncSocialsJson(); };
            window.syncSocialsJson = function () { const hiddenInput = document.getElementById('footer_socials_json'); if (hiddenInput) { hiddenInput.value = JSON.stringify(socialLinks); } };

            window.openCarouselGalleryModal = function () {
                document.getElementById('carousel-gallery-modal').classList.remove('hidden');
                document.getElementById('carousel-gallery-modal').classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Inicializar selección basada en lo que ya está en el carrusel
                refreshCarouselSelectionUI();
            };

            function refreshCarouselSelectionUI() {
                const modal = document.getElementById('carousel-gallery-modal');
                modal.querySelectorAll('.gallery-item-selectable').forEach(item => {
                    const isSelected = item.getAttribute('data-active') === '1';
                    if (isSelected) {
                        item.classList.add('selected', 'border-primary', 'ring-4', 'ring-primary/20');
                        item.classList.remove('border-slate-100', 'dark:border-slate-800');
                    } else {
                        item.classList.remove('selected', 'border-primary', 'ring-4', 'ring-primary/20');
                        item.classList.add('border-slate-100', 'dark:border-slate-800');
                    }
                });
            }

            window.toggleGallerySelection = function (element) {
                const isActive = element.getAttribute('data-active') === '1';
                if (isActive) {
                    element.setAttribute('data-active', '0');
                    element.classList.remove('selected', 'border-primary', 'ring-4', 'ring-primary/20');
                    element.classList.add('border-slate-100', 'dark:border-slate-800');
                } else {
                    element.setAttribute('data-active', '1');
                    element.classList.add('selected', 'border-primary', 'ring-4', 'ring-primary/20');
                    element.classList.remove('border-slate-100', 'dark:border-slate-800');
                }
            };

            window.saveCarouselSelection = function () {
                const selectedIds = [];
                document.querySelectorAll('.gallery-item-selectable[data-active="1"]').forEach(item => {
                    selectedIds.push(item.getAttribute('data-id'));
                });

                const btn = document.getElementById('save-carousel-btn');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">sync</span> Guardando...';

                fetch('{{ route("admin.gallery.bulk-carousel") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ image_ids: selectedIds })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Recargamos para ver los cambios en el carrusel
                        } else {
                            alert('Error al guardar: ' + (data.message || 'Error desconocido'));
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error en la conexión con el servidor');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
            };

            window.closeCarouselGalleryModal = function () {
                document.getElementById('carousel-gallery-modal').classList.add('hidden');
                document.getElementById('carousel-gallery-modal').classList.remove('flex');
                document.body.style.overflow = 'auto';
            };

            window.syncGalleryManual = function (btn) {
                const originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">sync</span> Sincronizando...';

                fetch('{{ route("admin.gallery.sync") }}?ajax=1', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mostrar mensaje de éxito (podemos usar una alerta simple o un toast si existe)
                            alert('✅ ' + data.message);
                            // Recargar la página pero manteniendo el hash o la sección actual si fuera posible.
                            // Dado que el site builder es dinámico, un reload volverá al inicio a menos que guardemos el estado.
                            // Guardamos la sección actual en sessionStorage antes de recargar
                            const activeSection = document.querySelector('.section-nav-item.active')?.getAttribute('data-section');
                            if (activeSection) {
                                sessionStorage.setItem('site_builder_active_section', activeSection);
                            }
                            location.reload();
                        } else {
                            alert('❌ Error: ' + data.message);
                            btn.disabled = false;
                            btn.innerHTML = originalContent;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('❌ Error al conectar con el servidor');
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    });
            };

            // Al cargar la página, restaurar la sección activa si existe
            document.addEventListener('DOMContentLoaded', function () {
                const savedSection = sessionStorage.getItem('site_builder_active_section');
                if (savedSection) {
                    switchSection(savedSection);
                    sessionStorage.removeItem('site_builder_active_section');
                }
            });
        </script>
    @endpush

    <!-- Modal: Carousel Gallery Picker -->
    <div id="carousel-gallery-modal"
        class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-[#0b0c11] w-full max-w-4xl rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
            <div
                class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-black">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Galería Global
                    </h3>
                    <p class="text-slate-500 text-xs font-bold mt-1 uppercase tracking-widest">Selecciona las fotos que
                        quieres mostrar en el carrusel</p>
                </div>
                <button onclick="closeCarouselGalleryModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 transition-all">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-10 custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                    @foreach($gallery as $item)
                        <div class="gallery-item-selectable relative aspect-square rounded-2xl overflow-hidden border-4 group transition-all cursor-pointer"
                            data-id="{{ $item->id }}" data-active="{{ $item->show_in_carousel ? '1' : '0' }}"
                            onclick="toggleGallerySelection(this)">

                            <img src="{{ $item->image_path }}" class="w-full h-full object-cover">

                            <div
                                class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-4xl">check_circle</span>
                            </div>

                            <!-- Checkbox visual indicator -->
                            <div
                                class="absolute top-3 right-3 w-6 h-6 rounded-full border-2 border-white/50 bg-black/20 flex items-center justify-center transition-all check-indicator">
                                <span class="material-symbols-outlined text-white text-xs font-black hidden">check</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <style>
                .gallery-item-selectable.selected .check-indicator {
                    background-color: var(--primary-color);
                    border-color: var(--primary-color);
                }

                .gallery-item-selectable.selected .check-indicator span {
                    display: block;
                }
            </style>

            <div
                class="p-8 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-black flex justify-between items-center">
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest italic">
                    Selecciona todas las que desees y presiona "Guardar"
                </p>
                <div class="flex gap-4">
                    <button onclick="closeCarouselGalleryModal()"
                        class="px-8 py-3 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-slate-300 transition-all">
                        Cancelar
                    </button>
                    <button id="save-carousel-btn" onclick="saveCarouselSelection()"
                        class="bg-primary text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Guardar Selección
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection