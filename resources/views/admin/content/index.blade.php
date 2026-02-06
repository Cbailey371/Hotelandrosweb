@extends('layouts.admin')

@section('title', 'Visual Site Builder')

@section('content')
    <style>
        /* ... existing styles ... */
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400&display=swap');

        :root {
            --bg-app: #F5F5F5;
            --bg-panel: #FFFFFF;
            --border-color: #E6E6E6;
            --text-primary: #1A1A1A;
            --text-secondary: #666666;
            --accent: #208B3A;
            --accent-hover: #186F2D;
            --selection: #3b82f6;
        }

        /* Interactive Editing Styles */
        .editable-element {
            cursor: pointer;
            position: relative;
            transition: outline 0.1s;
        }

        .editable-element:hover {
            outline: 2px dashed rgba(59, 130, 246, 0.5);
            outline-offset: -2px;
        }

        .editable-element.selected {
            outline: 3px solid var(--selection);
            outline-offset: -2px;
            z-index: 20;
        }

        .editable-element.selected::after {
            content: attr(data-label);
            position: absolute;
            top: -24px;
            left: 0;
            background: var(--selection);
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 4px 4px 0 0;
            font-weight: bold;
            pointer-events: none;
            white-space: nowrap;
        }

        /* Ensure text is editable */
        [contenteditable="true"]:focus {
            outline: none;
            background: rgba(59, 130, 246, 0.1);
        }

        /* Rest of existing styles... */
        body {
            background-color: var(--bg-app);
            font-family: 'Manrope', sans-serif;
            color: var(--text-primary);
            height: 100vh;
            overflow: hidden;
        }

        /* ================= PARENT LAYOUT OVERRIDES (Full Screen Fix) ================= */
        /* We need to break out of the parent .p-8 container */
        main>div.p-8.max-w-7xl {
            padding: 0 !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        /* Hide Global Header in Focus Mode */
        body.focus-mode main>header {
            display: none !important;
        }

        body.focus-mode>div>aside {
            display: none !important;
        }

        /* ================= LAYOUT GRID ================= */
        .builder-layout {
            display: grid;
            grid-template-columns: 60px 1fr 320px;
            /* Nav | Canvas | Properties */
            grid-template-rows: 64px 1fr;
            /* Header | Main */
            height: 100vh;
            /* Takes full viewport height */
            max-height: 100vh;
        }

        /* ================= HEADER ================= */
        .builder-header {
            grid-column: 1 / -1;
            background: var(--bg-panel);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 50;
        }

        .device-toggles button {
            color: var(--text-secondary);
            padding: 8px;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }

        .device-toggles button:hover,
        .device-toggles button.active {
            background: #F0F0F0;
            color: var(--text-primary);
        }

        .btn-focus-toggle {
            padding: 8px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            background: #F5F5F5;
            border: 1px solid var(--border-color);
        }

        .btn-focus-toggle:hover {
            background: #E0E0E0;
            color: var(--text-primary);
        }

        .btn-publish {
            background-color: var(--accent);
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(32, 139, 58, 0.2);
        }

        .btn-publish:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
        }

        /* ================= LEFT NAV (Icons) ================= */
        .builder-nav {
            background: var(--bg-panel);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            gap: 16px;
        }

        .nav-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .nav-icon-btn:hover {
            background: #F5F5F5;
            color: var(--text-primary);
        }

        .nav-icon-btn.active {
            background: #E8F5E9;
            /* Light Green Tint */
            color: var(--accent);
        }

        .nav-icon-btn.active::after {
            content: '';
            position: absolute;
            left: -12px;
            top: 8px;
            bottom: 8px;
            width: 4px;
            background: var(--accent);
            border-radius: 0 4px 4px 0;
        }

        /* ================= CENTER CANVAS ================= */
        .builder-canvas-wrapper {
            background: var(--bg-app);
            padding: 32px;
            overflow-y: auto;
            display: flex;
            justify-content: center;
        }

        .builder-canvas {
            background: white;
            width: 100%;
            max-width: 1000px;
            min-height: 800px;
            /* Aspect Ratio placeholder */
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-elevation);
            padding: 40px;
            position: relative;
        }

        /* Simulating the "Page" look inside the editor */
        .canvas-section-preview {
            border: 2px dashed transparent;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }

        .canvas-section-preview:hover {
            border-color: #2196F3;
            /* Blue "Selection" outline */
        }

        /* ================= RIGHT PROPERTIES ================= */
        .builder-properties {
            background: var(--bg-panel);
            border-left: 1px solid var(--border-color);
            overflow-y: auto;
        }

        .prop-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prop-section {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .prop-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #888;
            margin-bottom: 12px;
            display: block;
        }

        /* Custom Controls inspired by image */
        .control-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        /* Styled Inputs */
        .prop-input {
            background: #F7F7F7;
            border: 1px solid transparent;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            width: 100%;
            transition: all 0.2s;
        }

        .prop-input:focus {
            background: white;
            border-color: var(--accent);
            outline: none;
        }

        /* Sliders */
        .prop-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 4px;
            background: #E0E0E0;
            border-radius: 2px;
        }

        .prop-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Box Model Visualization (fake) */
        .box-model-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            background: #F7F7F7;
            padding: 16px;
            border-radius: 8px;
        }

        .box-input-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .box-input-wrap span {
            font-size: 10px;
            color: #999;
            margin-bottom: 4px;
        }

        .box-input-wrap input {
            text-align: center;
        }

        /* Quill Overrides for "Visual" feel */
        .ql-toolbar.ql-snow {
            border: none !important;
            background: transparent !important;
            margin-bottom: 8px;
        }

        .ql-container.ql-snow {
            border: 1px solid #eee !important;
            border-radius: 8px;
            transition: border 0.2s;
        }

        .ql-container.ql-snow:hover {
            border-color: #ddd !important;
        }

        .ql-editor {
            font-family: 'Manrope', sans-serif;
            /* Match UI font */
        }
    </style>

    <!-- 
                STRUCTURE:
                1. HEADER (Top)
                2. NAV (Left)
                3. CANVAS (Center) - Contains the Editors / Forms
                4. PROPERTIES (Right) - Contains the Visual Tweaks
            -->

    <form id="builder-form" action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data"
        class="builder-layout">
        @csrf
        <input type="hidden" name="active_section" id="active_section" value="{{ session('active_section', 'hero') }}">

        <!-- Hidden Backups for Safety -->
        @foreach(['hero_title_en', 'hero_subtitle_en', 'rooms_badge_en', 'rooms_title_en', 'rooms_description_en', 'cafe_description_en', 'cafe_feature1_desc_en', 'location_title_en', 'location_description_en', 'footer_policies_en'] as $field)
            <input type="hidden" name="{{ $field }}" value="{{ $settings[$field] ?? '' }}">
        @endforeach

        <!-- HEADER -->
        <header class="builder-header">
            <div class="flex items-center gap-4">
                <button type="button" onclick="toggleFocusMode()" class="btn-focus-toggle" title="Toggle Fullscreen Mode">
                    <span class="material-symbols-outlined text-[18px]">menu</span>
                </button>
                <span class="font-bold text-lg tracking-tight">Hotel Andros <span class="text-slate-400 font-normal">/
                        Editor</span></span>

                <!-- Device Toggles (Visual Only) -->
                <div class="device-toggles bg-slate-100 p-1 rounded-lg flex gap-1 ml-8">
                    <button type="button" class="active"><span
                            class="material-symbols-outlined text-[18px]">desktop_mac</span></button>
                    <button type="button"><span class="material-symbols-outlined text-[18px]">tablet_mac</span></button>
                    <button type="button"><span class="material-symbols-outlined text-[18px]">smartphone</span></button>
                </div>

                <div
                    class="flex items-center gap-2 ml-4 text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-[14px]">cloud_done</span>
                    All changes saved
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('welcome') }}" target="_blank"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    Preview
                </a>
                <button type="submit" class="btn-publish">
                    Publish
                </button>
            </div>
        </header>

        <!-- LEFT NAV -->
        <nav class="builder-nav">
            <!-- Tooltip logic would go here, simplified for now -->
            <div class="nav-icon-btn active" onclick="switchSection('hero')" data-section="hero" title="Hero">
                <span class="material-symbols-outlined">campaign</span>
            </div>
            <div class="nav-icon-btn" onclick="switchSection('carousel')" data-section="carousel" title="Carousel">
                <span class="material-symbols-outlined">imagesmode</span>
            </div>
            <div class="nav-icon-btn" onclick="switchSection('rooms')" data-section="rooms" title="Rooms">
                <span class="material-symbols-outlined">bed</span>
            </div>
            <div class="nav-icon-btn" onclick="switchSection('cafe')" data-section="cafe" title="Dining">
                <span class="material-symbols-outlined">restaurant</span>
            </div>
            <div class="nav-icon-btn" onclick="switchSection('attractions')" data-section="attractions" title="Attractions">
                <span class="material-symbols-outlined">tour</span>
            </div>
            <div class="nav-icon-btn" onclick="switchSection('location')" data-section="location" title="Location">
                <span class="material-symbols-outlined">location_on</span>
            </div>
            <div class="nav-icon-btn" onclick="switchSection('footer')" data-section="footer" title="Footer">
                <span class="material-symbols-outlined">dock_to_bottom</span>
            </div>
        </nav>

        <!-- CENTER CANVAS -->
        <main class="builder-canvas-wrapper">
            <div class="builder-canvas">

                <!-- HERO CANVAS (LIVE PREVIEW) -->
                <div id="section-hero" class="builder-section block h-full flex flex-col">
                    <div class="flex justify-between items-center mb-4 px-2 shrink-0">
                        <h2 class="text-xs font-bold text-slate-300 uppercase">Live Preview: Hero Section</h2>
                        <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-1 rounded">Click text to edit</span>
                    </div>

                    <!-- LIVE PREVIEW CONTAINER -->
                    <div class="flex-1 w-full bg-slate-900 overflow-hidden relative group rounded-md shadow-2xl border border-slate-800"
                        style="min-height: 500px;">

                        <!-- Layer 1: Bg Image -->
                        <div id="preview-hero-bg" class="absolute inset-0 editable-element" 
                             data-label="Background Image"
                             data-type="image"
                             data-prop-target="hero_image_input"
                             onclick="Builder.selectElement(this); Builder.highlightProperty('hero_image_btn')"
                             style='background-image: url("{{ $settings['hero_image'] ?? '/images/branding/hero.png' }}"); 
                                            background-size: cover; 
                                            background-position: center;
                                            opacity: {{ ($settings['hero_bg_opacity'] ?? 40) / 100 }};'>
                        </div>

                        <!-- Layer 2: Dynamic Overlay -->
                        <div id="preview-hero-overlay" 
                             class="absolute inset-0 transition-all duration-300 editable-element" 
                             data-label="Overlay Settings"
                             data-prop-target="input-hero-opacity"
                             style="background-color: {{ $settings['hero_overlay_color'] ?? '#000000' }}; 
                                            opacity: {{ ($settings['hero_overlay_opacity'] ?? 50) / 100 }};">
                        </div>

                        <!-- Gradient -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none">
                        </div>

                        <!-- Content -->
                        <div
                            class="relative z-10 w-full h-full flex flex-col justify-center items-center text-center p-10 md:p-20">

                            <!-- Title (Editable) -->
                            <div id="preview-hero-title" contenteditable="true"
                                class="text-white text-5xl md:text-7xl font-bold drop-shadow-2xl outline-none rounded px-4 transition-all empty:before:content-['Title'] editable-element"
                                data-label="Hero Title"
                                data-prop-target="input-hero-gap"
                                style="margin-bottom: {{ $settings['hero_gap'] ?? 24 }}px;">
                                {!! app()->getLocale() == 'es' ? ($settings['hero_title_es'] ?? '') : ($settings['hero_title_en'] ?? '') !!}
                            </div>

                            <!-- Subtitle (Editable) -->
                            <div id="preview-hero-subtitle" contenteditable="true"
                                class="max-w-3xl mx-auto text-white text-xl md:text-2xl drop-shadow-xl outline-none rounded px-4 transition-all empty:before:content-['Subtitle'] editable-element"
                                data-label="Hero Subtitle"
                                data-prop-target="input-hero-gap">
                                {!! app()->getLocale() == 'es' ? ($settings['hero_subtitle_es'] ?? '') : ($settings['hero_subtitle_en'] ?? '') !!}
                            </div>

                            <!-- Button Mockup -->
                            <div class="mt-12 opacity-80 pointer-events-none">
                                <span class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl shadow-xl">Descubrir
                                    Habitaciones</span>
                            </div>
                        </div>                    </div>

                    <!-- Hidden Inputs for Sync -->
                    <input type="hidden" name="hero_title_es" id="hero_title_es"
                        value="{{ $settings['hero_title_es'] ?? '' }}">
                    <input type="hidden" name="hero_subtitle_es" id="hero_subtitle_es"
                        value="{{ $settings['hero_subtitle_es'] ?? '' }}">
                </div>

                <!-- CAROUSEL CANVAS -->
                <div id="section-carousel" class="builder-section hidden">
                    <h2 class="text-xs font-bold text-slate-300 uppercase mb-8 pb-4 border-b">Editing: Carousel</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Add Button -->
                        <button type="button" onclick="openMediaLibrary('carousel')"
                            class="aspect-square rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 hover:border-blue-400 hover:text-blue-500 transition-all">
                            <span class="material-symbols-outlined text-3xl">add_photo_alternate</span>
                            <span class="text-xs font-bold mt-2">Add Slide</span>
                        </button>

                        @forelse($carousel_images as $image)
                            <div class="relative group aspect-square rounded-xl overflow-hidden shadow-sm">
                                <img src="{{ $image->image_path }}" class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                    <button type="button" onclick="toggleCarousel({{ $image->id }})"
                                        class="text-white hover:text-red-400">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>

                <!-- ROOMS CANVAS -->
                <div id="section-rooms" class="builder-section hidden">
                    <h2 class="text-xs font-bold text-slate-300 uppercase mb-8 pb-4 border-b">Editing: Rooms</h2>
                    <div class="space-y-8">
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Badge</label>
                            <div id="rooms_badge_es_editor">{!! $settings['rooms_badge_es'] ?? '' !!}</div>
                            <input type="hidden" name="rooms_badge_es" id="rooms_badge_es">
                        </div>
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Title</label>
                            <div id="rooms_title_es_editor">{!! $settings['rooms_title_es'] ?? '' !!}</div>
                            <input type="hidden" name="rooms_title_es" id="rooms_title_es">
                        </div>
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Description</label>
                            <div id="rooms_description_es_editor">{!! $settings['rooms_description_es'] ?? '' !!}</div>
                            <input type="hidden" name="rooms_description_es" id="rooms_description_es">
                        </div>
                    </div>
                </div>

                <!-- CAFE CANVAS -->
                <div id="section-cafe" class="builder-section hidden">
                    <h2 class="text-xs font-bold text-slate-300 uppercase mb-8 pb-4 border-b">Editing: Cafe</h2>
                    <div class="space-y-8">
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Description</label>
                            <div id="cafe_description_es_editor">{!! $settings['cafe_description_es'] ?? '' !!}</div>
                            <input type="hidden" name="cafe_description_es" id="cafe_description_es">
                        </div>
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Feature</label>
                            <div id="cafe_feature1_desc_es_editor">{!! $settings['cafe_feature1_desc_es'] ?? '' !!}</div>
                            <input type="hidden" name="cafe_feature1_desc_es" id="cafe_feature1_desc_es">
                        </div>
                    </div>
                </div>

                <!-- ATTRACTIONS CANVAS -->
                <div id="section-attractions" class="builder-section hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xs font-bold text-slate-300 uppercase">Editing: Attractions</h2>
                        <button type="button" onclick="openAddAttractionModal()"
                            class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded font-bold hover:bg-blue-700">+ New
                            Attraction</button>
                    </div>
                    <div class="space-y-4">
                        @foreach($attractions as $attr)
                            <div class="flex items-center gap-4 p-4 border rounded-xl hover:shadow-md transition-all bg-white">
                                <img src="{{ $attr->image_path }}" class="w-16 h-16 rounded-lg object-cover bg-slate-100">
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm">{{ $attr->title_es }}</h4>
                                    <div class="text-xs text-slate-500 truncate max-w-[300px]">
                                        {{ strip_tags($attr->description_es) }}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick='editAttraction(@json($attr))'
                                        class="w-8 h-8 rounded-full border flex items-center justify-center hover:bg-slate-50"><span
                                            class="material-symbols-outlined text-[16px]">edit</span></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- LOCATION CANVAS -->
                <div id="section-location" class="builder-section hidden">
                    <h2 class="text-xs font-bold text-slate-300 uppercase mb-8 pb-4 border-b">Editing: Location</h2>
                    <div class="space-y-8">
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Title</label>
                            <div id="location_title_es_editor">{!! $settings['location_title_es'] ?? '' !!}</div>
                            <input type="hidden" name="location_title_es" id="location_title_es">
                        </div>
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Description</label>
                            <div id="location_description_es_editor">{!! $settings['location_description_es'] ?? '' !!}
                            </div>
                            <input type="hidden" name="location_description_es" id="location_description_es">
                        </div>
                    </div>
                </div>

                <!-- FOOTER CANVAS -->
                <div id="section-footer" class="builder-section hidden">
                    <h2 class="text-xs font-bold text-slate-300 uppercase mb-8 pb-4 border-b">Editing: Footer</h2>
                    <div class="space-y-8">
                        <div class="canvas-section-preview p-4 hover:bg-slate-50">
                            <label class="block text-xs font-bold text-blue-500 mb-2">Policies</label>
                            <div id="footer_policies_es_editor">{!! $settings['footer_policies_es'] ?? '' !!}</div>
                            <input type="hidden" name="footer_policies_es" id="footer_policies_es">
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- RIGHT PROPERTIES -->
        <aside class="builder-properties">
            <div class="prop-header">
                <span>Properties</span>
                <span class="material-symbols-outlined text-[18px] text-slate-400">tune</span>
            </div>

            <!-- HERO PROPERTIES -->
            <div id="props-hero" class="prop-group">
                <div class="prop-section">
                    <label class="prop-label">Background</label>
                    <div class="mb-4">
                        <span class="text-xs font-medium block mb-2">Image Source</span>
                        <input type="hidden" name="hero_image" id="hero_image_input"
                            value="{{ $settings['hero_image'] ?? '' }}">
                        <button type="button" id="hero_image_btn"
                            onclick="openMediaLibrary('direct_update', 'hero_image_input')"
                            class="w-full py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded text-xs font-bold text-slate-600 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">image</span>
                            Change Image
                        </button>
                    </div>
                    <label class="prop-label mt-4">Spacing System</label>
                    <div class="box-model-grid mb-6">
                        <div></div>
                        <div class="box-input-wrap"><span>Gap</span><input type="text" readonly
                                value="{{ $settings['hero_gap'] ?? 24 }}" id="gap-val-disp"
                                class="w-12 h-6 text-xs border rounded bg-white"></div>
                        <div></div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs font-medium">Vertical Gap</span>
                            <span class="text-xs text-slate-500">px</span>
                        </div>
                        <input type="range" name="hero_gap" id="input-hero-gap" class="prop-slider" min="0" max="100"
                            step="4" value="{{ $settings['hero_gap'] ?? 24 }}"
                            oninput="updateLivePreview('gap', this.value)">
                    </div>
                </div>

                <div class="prop-section">
                    <label class="prop-label">Effects</label>
                    <div class="mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs font-medium">Overlay Opacity</span>
                            <span class="text-xs text-slate-500">%</span>
                        </div>
                        <input type="range" name="hero_overlay_opacity" id="input-hero-opacity" class="prop-slider" min="0"
                            max="100" step="5" value="{{ $settings['hero_overlay_opacity'] ?? 50 }}"
                            oninput="updateLivePreview('opacity', this.value)">
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-medium block mb-2">Overlay Color</span>
                        <div class="flex gap-2">
                            <input type="color" name="hero_overlay_color" id="input-hero-color"
                                value="{{ $settings['hero_overlay_color'] ?? '#000000' }}"
                                oninput="updateLivePreview('color', this.value)"
                                class="block w-8 h-8 rounded border-none p-0 cursor-pointer">
                            <input type="text" value="{{ $settings['hero_overlay_color'] ?? '#000000' }}"
                                class="prop-input flex-1 uppercase" readonly>
                        </div>
                    </div>
                </div>
            </div>

        </aside>

    </form>

    <!-- MODALS (SAME AS BEFORE) -->
    <!-- Media Library Modal -->
    <div id="media-library-modal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeMediaLibrary()"></div>
        <div
            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl max-h-[80vh] bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold">Select Media</h3>
                <button onclick="closeMediaLibrary()"
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center"><span
                        class="material-symbols-outlined text-sm">close</span></button>
            </div>
            <div class="flex-1 overflow-y-auto p-8 grid grid-cols-5 gap-4">
                @foreach($gallery as $item)
                    <div class="aspect-square rounded-lg overflow-hidden border cursor-pointer hover:ring-2 ring-blue-500"
                        onclick="selectImageFromLibrary('{{ $item->image_path }}', {{ $item->id }})">
                        <img src="{{ $item->image_path }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Attraction Modal -->
    <div id="attraction-form-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
            onclick="document.getElementById('attraction-form-modal').classList.add('hidden')"></div>
        <div
            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-8 bg-white rounded-2xl shadow-2xl">
            <h3 class="text-xl font-bold mb-6" id="attraction-modal-title">New Attraction</h3>
            <form id="attraction-form" action="{{ route('admin.attractions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div id="attraction-method-container"></div>
                <input type="hidden" name="id" id="attraction-id-field">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="title_es" placeholder="Name (Spanish)" class="prop-input" required>
                    <input type="text" name="title_en" placeholder="Name (English)" class="prop-input" required>
                </div>
                <div class="input-group">
                    <label class="prop-label">Description (ES)</label>
                    <div class="h-24">
                        <div id="attraction_description_es_editor" class="h-full bg-slate-50 border rounded"></div>
                    </div>
                    <input type="hidden" name="description_es" id="attraction_description_es">
                </div>
                <div class="input-group">
                    <label class="prop-label">Description (EN)</label>
                    <div class="h-24">
                        <div id="attraction_description_en_editor" class="h-full bg-slate-50 border rounded"></div>
                    </div>
                    <input type="hidden" name="description_en" id="attraction_description_en">
                </div>
                <div class="flex gap-4 items-center">
                    <img src="" id="attraction-preview" class="w-16 h-12 rounded bg-slate-100 object-cover">
                    <input type="hidden" name="image_path" id="attraction_image_input">
                    <button type="button" onclick="openMediaLibrary('attraction')"
                        class="text-xs bg-slate-100 px-3 py-2 rounded font-bold">Select Image</button>
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded font-bold">Save</button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            // ================= VS BUILDER CORE =================
            const Builder = {
                activeElement: null,

                init() {
                    this.attachListeners();
                    this.restoreState();
                },

                attachListeners() {
                    // Canvas Click Handling
                    document.getElementById('section-hero').addEventListener('click', (e) => {
                        const target = e.target.closest('.editable-element');
                        if (target) {
                            e.stopPropagation(); // Stop bubbling
                            this.selectElement(target);
                        } else {
                            // Clicked background/void
                            // this.deselectAll();
                        }
                    });

                    // Input Sync (Real-time Preview)
                    document.querySelectorAll('input[oninput]').forEach(input => {
                        // Ensure we respect existing oninput but add our sync
                    });
                },

                selectElement(el) {
                    // 1. Visual Selection
                    this.deselectAll();
                    el.classList.add('selected');
                    this.activeElement = el;

                    // 2. Focus Property in Sidebar
                    const propId = el.dataset.propTarget;
                    if (propId) {
                        this.highlightProperty(propId);
                    }

                    // 3. Handle specific types
                    if (el.dataset.type === 'image') {
                        // Automatically open media library? Maybe too aggressive.
                        // Just scroll to the image input
                    }
                },

                deselectAll() {
                    document.querySelectorAll('.editable-element').forEach(el => el.classList.remove('selected'));
                    this.activeElement = null;
                },

                highlightProperty(propId) {
                    const propEl = document.getElementById(propId);
                    if (propEl) {
                        // Scroll Sidebar to element
                        propEl.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        // Flash effect
                        propEl.parentElement.classList.add('bg-blue-50');
                        setTimeout(() => propEl.parentElement.classList.remove('bg-blue-50'), 1000);

                        // Focus if input
                        if (propEl.tagName === 'INPUT') propEl.focus();
                    }
                },

                restoreState() {
                    // Restore section
                    switchSection(document.getElementById('active_section').value || 'hero');

                    // Restore focus mode
                    if (localStorage.getItem('builder_focus_mode') === '1') {
                        document.body.classList.add('focus-mode');
                    }
                }
            };

            // LIVE PREVIEW LOGIC (Legacy + New)
            window.updateLivePreview = function (type, value) {
                if (type === 'gap') {
                    document.getElementById('preview-hero-title').style.marginBottom = value + 'px';
                    document.getElementById('gap-val-disp').value = value;
                } else if (type === 'opacity') {
                    document.getElementById('preview-hero-overlay').style.opacity = value / 100;
                } else if (type === 'color') {
                    document.getElementById('preview-hero-overlay').style.backgroundColor = value;
                }
            };

            // Re-implement legacy functions for compatibility
            window.switchSection = function (sectionId) {
                document.querySelectorAll('.nav-icon-btn').forEach(el => el.classList.remove('active'));
                document.querySelector(`[data-section="${sectionId}"]`)?.classList.add('active');

                document.querySelectorAll('.builder-section').forEach(el => {
                    el.classList.remove('block');
                    el.classList.add('hidden');
                });
                document.getElementById('section-' + sectionId)?.classList.remove('hidden');
                document.getElementById('section-' + sectionId)?.classList.add('block');

                document.getElementById('active_section').value = sectionId;
            }

            window.toggleFocusMode = function () {
                document.body.classList.toggle('focus-mode');
                const isFocus = document.body.classList.contains('focus-mode');
                localStorage.setItem('builder_focus_mode', isFocus ? '1' : '0');
            };

            // MEDIA LIBRARY (Enhanced)
            let mediaLibraryTarget = null;
            let activeImageInput = null;

            window.openMediaLibrary = function (target, inputId = null) {
                mediaLibraryTarget = target;
                if (inputId) activeImageInput = inputId;
                document.getElementById('media-library-modal').classList.remove('hidden');
            };

            window.closeMediaLibrary = function () {
                document.getElementById('media-library-modal').classList.add('hidden');
            };

            window.selectImageFromLibrary = function (path, id) {
                if (mediaLibraryTarget === 'carousel') {
                    fetch(`/admin/gallery/${id}/toggle-carousel`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(() => window.location.reload());
                } else if (mediaLibraryTarget === 'attraction') {
                    document.getElementById('attraction_image_input').value = path;
                    document.getElementById('attraction-preview').src = path;
                } else if (mediaLibraryTarget === 'direct_update') {
                    if (activeImageInput) {
                        document.getElementById(activeImageInput).value = path;
                        
                        if (activeImageInput === 'hero_image_input') {
                            document.getElementById('preview-hero-bg').style.backgroundImage = `url("${path}")`;
                        }
                    }
                }
                closeMediaLibrary();
            };

            document.addEventListener('DOMContentLoaded', () => {
                Builder.init();

                // Sync ContentEditable
                ['hero_title_es', 'hero_subtitle_es'].forEach(id => {
                    const preview = document.getElementById('preview-' + id.replace(/_/g, '-')); // preview-hero-title-es ?? Check IDs
                    // The IDs in HTML are: preview-hero-title, preview-hero-subtitle
                    // The Inputs are: hero_title_es, hero_subtitle_es

                    // Manual Mapping
                    const map = {
                        'hero_title_es': 'preview-hero-title',
                        'hero_subtitle_es': 'preview-hero-subtitle'
                    };

                    const pId = map[id];
                    const previewEl = document.getElementById(pId);
                    const inputEl = document.getElementById(id);

                    if (previewEl && inputEl) {
                        previewEl.addEventListener('input', function () {
                            inputEl.value = this.innerText;
                        });

                        // Prevent new lines if desired
                        previewEl.addEventListener('keypress', (e) => {
                            if (e.key === 'Enter') {
                                // e.preventDefault();
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection