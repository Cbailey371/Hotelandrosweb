<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Editor - {{ $page->name }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <!-- Quill (if needed for components) -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Editor Specific Overrides */
        .cursor-wait {
            cursor: wait;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 h-screen overflow-hidden">
    <script>
        window.initialPageData = @json($page);
    </script>
    <div x-data="setupEditor(window.initialPageData)" class="flex h-screen overflow-hidden bg-slate-100 font-sans">

        <!-- TOP BAR -->
        <header
            class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 z-50 flex items-center justify-between px-6 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="font-bold text-slate-800">Visual Editor <span class="text-slate-400 font-normal">/
                        {{ $page->name }}</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex bg-slate-100 rounded-lg p-1 border border-slate-200">
                    <button class="p-2 rounded hover:bg-white hover:shadow-sm"
                        :class="{'bg-white shadow-sm': previewWidth === '100%'}" @click="setPreview('100%')"
                        title="Desktop"><span class="material-symbols-outlined text-sm">desktop_windows</span></button>
                    <button class="p-2 rounded hover:bg-white hover:shadow-sm"
                        :class="{'bg-white shadow-sm': previewWidth === '768px'}" @click="setPreview('768px')"
                        title="Tablet"><span class="material-symbols-outlined text-sm">tablet_mac</span></button>
                    <button class="p-2 rounded hover:bg-white hover:shadow-sm"
                        :class="{'bg-white shadow-sm': previewWidth === '375px'}" @click="setPreview('375px')"
                        title="Mobile"><span class="material-symbols-outlined text-sm">smartphone</span></button>
                </div>

                <button @click="savePage()"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg shadow-green-600/20 flex items-center gap-2 transition-all"
                    :class="{'opacity-75 cursor-wait': saving}">
                    <span class="material-symbols-outlined text-sm" x-show="!saving">save</span>
                    <span class="material-symbols-outlined text-sm animate-spin" x-show="saving">sync</span>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
        </header>

        <!-- LEFT SIDEBAR (Layers/Add) -->
        <aside class="w-16 bg-white border-r border-slate-200 pt-20 flex flex-col items-center gap-4 z-40">
            <button @click="showAddModal = true"
                class="w-10 h-10 rounded-lg bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-600 flex items-center justify-center transition-colors"
                title="Add Section">
                <span class="material-symbols-outlined">add_circle</span>
            </button>
            <button @click="showLayers = !showLayers"
                class="w-10 h-10 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors"
                :class="{'bg-blue-50 text-blue-600': showLayers}" title="Layers">
                <span class="material-symbols-outlined">layers</span>
            </button>
        </aside>

        <!-- LAYERS DRAWER -->
        <div x-show="showLayers" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0"
            class="w-64 bg-slate-50 border-r border-slate-200 pt-20 flex flex-col z-30 shadow-xl absolute left-16 h-full"
            style="display: none;">

            <div class="px-4 pb-4 border-b border-slate-200 flex justify-between items-center">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Layers</span>
                <button @click="showLayers = false" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-2 space-y-2">
                <template x-for="(section, index) in sections" :key="section.id || index">
                    <div @click="selectSection(index)"
                        class="flex items-center gap-3 p-2 rounded-lg cursor-pointer transition-all border border-transparent group"
                        :class="{'bg-white shadow-sm border-slate-200': activeSection === index, 'hover:bg-slate-100': activeSection !== index}">

                        <div class="text-slate-400 cursor-grab section-handle-layer">
                            <span class="material-symbols-outlined text-sm">drag_indicator</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-700 truncate capitalize" x-text="section.type"></p>
                            <p class="text-[10px] text-slate-400 truncate" x-text="'ID: ' + (section.id || index)"></p>
                        </div>

                        <button @click.stop="sections.splice(index, 1); if(activeSection===index) deselect()"
                            class="text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </template>

                <div x-show="sections.length === 0" class="text-center py-10 text-slate-400 text-xs">
                    No sections yet.
                </div>
            </div>
        </div>

        <!-- CANVAS -->
        <main class="flex-1 pt-20 pb-10 px-10 overflow-auto flex justify-center bg-slate-100" @click="deselect()">
            <div class="w-full bg-white min-h-full shadow-2xl transition-all duration-300 origin-top"
                :style="{ maxWidth: previewWidth }" id="editor-canvas">

                @if(isset($page->content['sections']))
                    @foreach($page->content['sections'] as $index => $section)
                        <div class="relative group outline-2 outline-transparent hover:outline-blue-400 hover:outline-dashed transition-all"
                            :class="{'outline-blue-600 outline-solid ring-2 ring-blue-600/20': activeSection === {{ $index }}}"
                            @click.stop="selectSection({{ $index }})" data-section-index="{{ $index }}">

                            <!-- Section Toolbar -->
                            <div
                                class="absolute top-0 right-0 -translate-y-full bg-blue-600 text-white text-xs px-2 py-1 rounded-t-lg shadow-sm flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                <button
                                    class="section-handle cursor-grab active:cursor-grabbing hover:text-blue-200 mt-0.5"><span
                                        class="material-symbols-outlined text-sm">drag_indicator</span></button>
                                <span class="font-bold uppercase tracking-wider self-center">{{ $section['type'] }}</span>
                                <button class="hover:text-blue-200"><span
                                        class="material-symbols-outlined text-[10px]">arrow_upward</span></button>
                                <button class="hover:text-blue-200"><span
                                        class="material-symbols-outlined text-[10px]">arrow_downward</span></button>
                                <button class="hover:text-red-200"><span
                                        class="material-symbols-outlined text-[10px]">delete</span></button>
                            </div>

                            <!-- Component Render -->
                            <x-dynamic-component :component="'sections.' . $section['type']" :data="$section['data']"
                                :rooms="$rooms ?? []" :carouselImages="$carouselImages ?? []" :attractions="$attractions ?? []"
                                mode="editor" />
                        </div>
                    @endforeach
                @endif

            </div>
        </main>

        <!-- RIGHT SIDEBAR (Properties) -->
        <aside class="w-80 bg-white border-l border-slate-200 pt-20 px-6 overflow-auto z-40 transition-all"
            x-show="activeElement || activeSection !== null" x-transition:enter="translate-x-full"
            x-transition:enter-end="translate-x-0">

            <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Properties</span>
                <button @click="deselect()" class="text-slate-400 hover:text-slate-600"><span
                        class="material-symbols-outlined text-sm">close</span></button>
            </div>

            <!-- DYNAMIC PROPERTIES -->
            <div x-show="activeElement">
                <h3 class="font-bold text-lg mb-4" x-text="activeElementLabel">Element</h3>

                <div class="space-y-4">
                    <!-- Text Content -->
                    <div x-show="activeType === 'text'">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Content</label>
                        <textarea x-model="activeValue" @input="updateElement()"
                            class="w-full text-sm border-slate-200 rounded p-2 focus:ring-blue-500 focus:border-blue-500 mb-4"
                            rows="3"></textarea>

                        <!-- Typography Controls -->
                        <div class="border-t border-slate-100 pt-4 space-y-4">
                            <span
                                class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Typography</span>

                            <!-- Color -->
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-600">Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" x-model="activeStyleColor"
                                        @input="updateStyle('color', $el.value)"
                                        class="w-8 h-8 rounded cursor-pointer border-0 p-0">
                                </div>
                            </div>

                            <!-- Font Size -->
                            <div>
                                <label class="text-xs font-bold text-slate-600 mb-1 block">Size</label>
                                <select x-model="activeStyleSize" @change="updateStyle('fontSize', $el.value)"
                                    class="w-full text-xs border-slate-200 rounded p-2 text-slate-600">
                                    <option value="">Default</option>
                                    <option value="1rem">Small (1rem)</option>
                                    <option value="1.25rem">Normal (1.25rem)</option>
                                    <option value="1.5rem">Large (1.5rem)</option>
                                    <option value="2.25rem">Title (2.25rem)</option>
                                    <option value="3rem">Big Title (3rem)</option>
                                    <option value="4.5rem">Huge (4.5rem)</option>
                                </select>
                            </div>

                            <!-- Font Family -->
                            <div>
                                <label class="text-xs font-bold text-slate-600 mb-1 block">Font</label>
                                <select x-model="activeStyleFont" @change="updateStyle('fontFamily', $el.value)"
                                    class="w-full text-xs border-slate-200 rounded p-2 text-slate-600">
                                    <option value="">Default</option>
                                    <option value="'Inter', sans-serif">Inter (Sans)</option>
                                    <option value="'Merriweather', serif">Merriweather (Serif)</option>
                                    <option value="'Courier New', monospace">Courier (Mono)</option>
                                    <option value="'Playfair Display', serif">Playfair (Elegant)</option>
                                </select>
                            </div>

                            <!-- Text Align -->
                            <div>
                                <label class="text-xs font-bold text-slate-600 mb-1 block">Alignment</label>
                                <div class="flex bg-slate-100 rounded p-1 gap-1">
                                    <button @click="updateStyle('textAlign', 'left'); activeStyleAlign = 'left'"
                                        class="flex-1 py-1 rounded hover:bg-white hover:shadow text-slate-500"
                                        :class="{'bg-white shadow text-blue-600': activeStyleAlign === 'left'}">
                                        <span class="material-symbols-outlined text-sm">format_align_left</span>
                                    </button>
                                    <button @click="updateStyle('textAlign', 'center'); activeStyleAlign = 'center'"
                                        class="flex-1 py-1 rounded hover:bg-white hover:shadow text-slate-500"
                                        :class="{'bg-white shadow text-blue-600': activeStyleAlign === 'center'}">
                                        <span class="material-symbols-outlined text-sm">format_align_center</span>
                                    </button>
                                    <button @click="updateStyle('textAlign', 'right'); activeStyleAlign = 'right'"
                                        class="flex-1 py-1 rounded hover:bg-white hover:shadow text-slate-500"
                                        :class="{'bg-white shadow text-blue-600': activeStyleAlign === 'right'}">
                                        <span class="material-symbols-outlined text-sm">format_align_right</span>
                                    </button>
                                    <button @click="updateStyle('textAlign', 'justify'); activeStyleAlign = 'justify'"
                                        class="flex-1 py-1 rounded hover:bg-white hover:shadow text-slate-500"
                                        :class="{'bg-white shadow text-blue-600': activeStyleAlign === 'justify'}">
                                        <span class="material-symbols-outlined text-sm">format_align_justify</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Source -->
                    <div x-show="activeType === 'image'">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Image Data</label>
                        <input type="text" x-model="activeValue"
                            class="w-full text-xs border-slate-200 rounded p-2 mb-2 text-slate-400" readonly>
                        <button @click="showImageModal = true"
                            class="w-full py-2 bg-slate-100 hover:bg-slate-200 rounded text-xs font-bold text-slate-600 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">image</span> Choose Image
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="!activeElement && activeSection !== null">
                <h3 class="font-bold text-lg mb-4">Section Settings</h3>
                <p class="text-sm text-slate-500">Select an element to edit properties.</p>

                <div class="mt-4 space-y-6">
                    <!-- Spacing -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Spacing & Layout
                        </h4>

                        <!-- Gap (existing) -->
                        <div x-show="sections[activeSection]?.data?.gap !== undefined" class="mb-3">
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Inner Gap</label>
                            <input type="range" x-model="sections[activeSection].data.gap" min="0" max="100"
                                class="w-full accent-blue-600">
                        </div>

                        <!-- Padding Y -->
                        <div class="mb-3">
                            <label class="text-xs font-bold text-slate-500 mb-1 block flex justify-between">
                                <span>Vertical Padding</span>
                                <span x-text="(sections[activeSection]?.data?.padding_y || 20) + 'px'"
                                    class="font-normal text-slate-400"></span>
                            </label>
                            <input type="range" x-model="sections[activeSection].data.padding_y" min="0" max="200"
                                step="4" class="w-full accent-blue-600">
                        </div>

                        <!-- Container Width -->
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-600">Full Width</label>
                            <button
                                @click="sections[activeSection].data.container_width = sections[activeSection].data.container_width === 'full' ? 'boxed' : 'full'"
                                class="w-10 h-5 rounded-full relative transition-colors duration-200"
                                :class="sections[activeSection].data.container_width === 'full' ? 'bg-blue-600' : 'bg-slate-300'">
                                <div class="w-3 h-3 bg-white rounded-full absolute top-1 transition-all duration-200"
                                    :class="sections[activeSection].data.container_width === 'full' ? 'left-6' : 'left-1'">
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Background & Effects -->
                    <div>
                        <h4
                            class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 border-t border-slate-100 pt-3">
                            Background</h4>

                        <!-- Overlay Opacity -->
                        <div class="mb-3">
                            <label class="text-xs font-bold text-slate-500 mb-1 block flex justify-between">
                                <span>Overlay Opacity</span>
                                <span x-text="(sections[activeSection]?.data?.overlay_opacity || 0) + '%'"
                                    class="font-normal text-slate-400"></span>
                            </label>
                            <input type="range" x-model="sections[activeSection].data.overlay_opacity" min="0" max="90"
                                class="w-full accent-blue-600">
                        </div>

                        <!-- Blur -->
                        <div class="mb-3">
                            <label class="text-xs font-bold text-slate-500 mb-1 block flex justify-between">
                                <span>Blur Effect</span>
                                <span x-text="(sections[activeSection]?.data?.bg_blur || 0) + 'px'"
                                    class="font-normal text-slate-400"></span>
                            </label>
                            <input type="range" x-model="sections[activeSection].data.bg_blur" min="0" max="20"
                                class="w-full accent-blue-600">
                        </div>
                    </div>
                </div>
            </div>

        </aside>

        <!-- ADD SECTION MODAL -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative z-10 overflow-hidden animate-fade-in-up">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-lg text-slate-800">Add Section</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[60vh] overflow-y-auto">
                    <!-- Component Cards -->
                    <button @click="addSection('hero')"
                        class="flex flex-col items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div
                            class="w-full h-20 bg-slate-200 rounded flex items-center justify-center text-slate-400 group-hover:text-blue-500">
                            <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                        <span class="font-bold text-sm text-slate-600 group-hover:text-blue-700">Hero</span>
                    </button>
                    <button @click="addSection('rooms')"
                        class="flex flex-col items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div
                            class="w-full h-20 bg-slate-200 rounded flex items-center justify-center text-slate-400 group-hover:text-blue-500">
                            <span class="material-symbols-outlined text-4xl">bed</span>
                        </div>
                        <span class="font-bold text-sm text-slate-600 group-hover:text-blue-700">Rooms</span>
                    </button>
                    <button @click="addSection('cafe')"
                        class="flex flex-col items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div
                            class="w-full h-20 bg-slate-200 rounded flex items-center justify-center text-slate-400 group-hover:text-blue-500">
                            <span class="material-symbols-outlined text-4xl">coffee</span>
                        </div>
                        <span class="font-bold text-sm text-slate-600 group-hover:text-blue-700">Café</span>
                    </button>
                    <button @click="addSection('gallery')"
                        class="flex flex-col items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div
                            class="w-full h-20 bg-slate-200 rounded flex items-center justify-center text-slate-400 group-hover:text-blue-500">
                            <span class="material-symbols-outlined text-4xl">photo_library</span>
                        </div>
                        <span class="font-bold text-sm text-slate-600 group-hover:text-blue-700">Gallery</span>
                    </button>
                    <button @click="addSection('attractions')"
                        class="flex flex-col items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div
                            class="w-full h-20 bg-slate-200 rounded flex items-center justify-center text-slate-400 group-hover:text-blue-500">
                            <span class="material-symbols-outlined text-4xl">pin_drop</span>
                        </div>
                        <span class="font-bold text-sm text-slate-600 group-hover:text-blue-700">Attractions</span>
                    </button>
                    <button @click="addSection('location')"
                        class="flex flex-col items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div
                            class="w-full h-20 bg-slate-200 rounded flex items-center justify-center text-slate-400 group-hover:text-blue-500">
                            <span class="material-symbols-outlined text-4xl">map</span>
                        </div>
                        <span class="font-bold text-sm text-slate-600 group-hover:text-blue-700">Location</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- IMAGE LIBRARY MODAL -->
        <div x-show="showImageModal" class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showImageModal = false"></div>
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative z-10 overflow-hidden animate-fade-in-up flex flex-col max-h-[80vh]">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-lg text-slate-800">Select Image</h3>
                    <button @click="showImageModal = false" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Upload Placeholder -->
                    <div
                        class="aspect-square bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center text-slate-400 hover:border-blue-500 hover:text-blue-500 cursor-pointer transition-all">
                        <span class="material-symbols-outlined text-4xl mb-2">cloud_upload</span>
                        <span class="text-xs font-bold uppercase">Upload New</span>
                    </div>

                    <!-- Gallery Images -->
                    @foreach($carouselImages as $img)
                        <div @click="selectImage('{{ asset('storage/' . $img->image_path) }}')"
                            class="aspect-square bg-slate-100 rounded-lg overflow-hidden cursor-pointer hover:ring-4 ring-blue-500 transition-all relative group">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                        </div>
                    @endforeach

                    <!-- Static Fallbacks for Demo -->
                    <div @click="selectImage('/images/hero.jpg')"
                        class="aspect-square bg-slate-100 rounded-lg overflow-hidden cursor-pointer hover:ring-4 ring-blue-500 transition-all relative group">
                        <img src="/images/hero.jpg" class="w-full h-full object-cover">
                    </div>
                    <div @click="selectImage('/images/cafe.png')"
                        class="aspect-square bg-slate-100 rounded-lg overflow-hidden cursor-pointer hover:ring-4 ring-blue-500 transition-all relative group">
                        <img src="/images/cafe.png" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- ALPINE LOGIC -->
        <!-- Alpine Editor Logic -->

        <script>
            window.setupEditor = (initialPage) => ({
                page: initialPage,
                sections: initialPage.content?.sections || [],
                saving: false,
                activeSection: null,
                activeElement: null,
                activeElementLabel: 'Element',
                activeType: 'text',
                activeField: null,
                activeField: null,
                previewWidth: '100%',
                showAddModal: false,
                showLayers: false,
                showImageModal: false,

                // Default data for new sections
                componentDefaults: {
                    'hero': { title_es: 'Nuevo Hero', title_en: 'New Hero', bg_image: '/images/hero.jpg', gap: 24, overlay_opacity: 50, overlay_color: '#000000' },
                    'rooms': { title_es: 'Habitaciones', title_en: 'Rooms' },
                    'cafe': { title_es: 'Café', title_en: 'Cafe', description_es: 'Descripción...', image: '/images/cafe.png' },
                    'gallery': { title_es: 'Galería', title_en: 'Gallery' },
                    'location': { title_es: 'Ubicación', title_en: 'Location' },
                    'attractions': { title_es: 'Atracciones', title_en: 'Attractions' }
                },

                addSection(type) {
                    this.showAddModal = false;
                    const data = this.componentDefaults[type] || {};
                    // Generate a cleaner ID
                    const newId = type + '_' + Date.now();
                    const newIndex = this.sections.length; // Approximate next index

                    // 1. Add to state
                    const newSection = { id: newId, type: type, data: data, settings: { visible: true } };
                    this.sections.push(newSection);

                    // 2. Fetch HTML
                    fetch('/admin/editor/render', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ type, data })
                    })
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`HTTP error! status: ${res.status}`);
                            }
                            return res.json();
                        })
                        .then(response => {
                            console.log('Add Section Response:', response);
                            if (response.html) {
                                // 3. Append to DOM wrapper
                                const canvas = document.getElementById('editor-canvas');
                                const wrapper = document.createElement('div');
                                wrapper.className = "relative group outline-2 outline-transparent hover:outline-blue-400 hover:outline-dashed transition-all";
                                wrapper.setAttribute('data-section-index', newIndex);

                                wrapper.innerHTML = `
                            <div class="absolute top-0 right-0 -translate-y-full bg-blue-600 text-white text-xs px-2 py-1 rounded-t-lg shadow-sm flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                <button class="section-handle cursor-grab active:cursor-grabbing hover:text-blue-200 mt-0.5"><span class="material-symbols-outlined text-sm">drag_indicator</span></button>
                                <span class="font-bold uppercase tracking-wider self-center">${type}</span>
                                <button class="hover:text-red-200" onclick="this.closest('[data-section-index]').remove()"><span class="material-symbols-outlined text-[10px]">delete</span></button>
                            </div>
                            ${response.html}
                        `;

                                // Replicate the Alpine click behavior
                                wrapper.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    this.selectSection(newIndex);
                                });

                                // IMPORTANT: Add "editor" mode listeners for new elements if using contenteditable?
                                // The 'input' listener is on the root $el, so it should catch bubbles.

                                canvas.appendChild(wrapper);

                                // Scroll to bottom
                                wrapper.scrollIntoView({ behavior: 'smooth' });
                            }
                        });
                },

                init() {
                    console.log('Editor Initialized', this.sections);

                    // Initialize Sortable
                    const canvas = document.getElementById('editor-canvas');
                    if (canvas && window.Sortable) {
                        new Sortable(canvas, {
                            animation: 150,
                            handle: '.section-handle',
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                console.log('Reordered', evt.oldIndex, evt.newIndex);
                            }
                        });
                    }

                    // Contenteditable listener
                    this.$el.addEventListener('input', (e) => {
                        const el = e.target;
                        if (el.isContentEditable && el.dataset.field) {
                            this.syncFromDOM(el);
                        }
                    });
                },

                selectSection(index) {
                    this.activeSection = index;
                    this.activeElement = null;

                    // Smooth scroll to section
                    setTimeout(() => {
                        const el = document.querySelector(`[data-section-index="${index}"]`);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }, 50);
                },

                selectElement(el) {
                    this.activeElement = el;
                    this.activeElementLabel = el.dataset.label || 'Element';
                    this.activeType = el.dataset.type || 'text';
                    this.activeField = el.dataset.field;

                    // Auto-select parent section
                    const sectionEl = el.closest('[data-section-index]');
                    if (sectionEl) {
                        this.activeSection = parseInt(sectionEl.dataset.sectionIndex);
                    }
                },

                get activeStyleColor() {
                    if (!this.activeSection || !this.activeField) return '#000000';
                    return this.sections[this.activeSection].data[this.activeField + '_color'] || '#ffffff';
                },

                set activeStyleColor(val) { /* handled by updateStyle */ },

                get activeStyleSize() {
                    if (!this.activeSection || !this.activeField) return '';
                    return this.sections[this.activeSection].data[this.activeField + '_fontSize'] || '';
                },

                set activeStyleSize(val) { /* handled by updateStyle */ },

                get activeStyleFont() {
                    if (!this.activeSection || !this.activeField) return '';
                    return this.sections[this.activeSection].data[this.activeField + '_fontFamily'] || '';
                },
                set activeStyleFont(val) { }, // handled by updateStyle

                get activeStyleAlign() {
                    if (!this.activeSection || !this.activeField) return 'left';
                    return this.sections[this.activeSection].data[this.activeField + '_textAlign'] || 'left';
                },
                set activeStyleAlign(val) { }, // handled by updateStyle

                updateStyle(prop, val) {
                    if (this.activeSection !== null && this.activeField && this.activeElement) {
                        // 1. Update Data
                        this.sections[this.activeSection].data[this.activeField + '_' + prop] = val;

                        // 2. Update DOM
                        this.activeElement.style[prop] = val;
                    }
                },

                selectImage(url) {
                    if (this.activeSection !== null && this.activeType === 'image') {
                        // Update Image Field
                        if (this.activeField) {
                            this.sections[this.activeSection].data[this.activeField] = url;
                        }
                        // Fallback for background images (if stored differently)
                        else if (this.activeElement.hasAttribute('data-field')) {
                            const field = this.activeElement.getAttribute('data-field');
                            this.sections[this.activeSection].data[field] = url;
                        }

                        // Update DOM
                        if (this.activeElement.tagName === 'IMG') {
                            this.activeElement.src = url;
                        } else {
                            this.activeElement.style.backgroundImage = `url("${url}")`;
                        }
                    }
                    this.showImageModal = false;
                },

                get activeValue() {
                    if (this.activeSection === null || !this.activeField) return '';
                    if (!this.sections[this.activeSection]) return '';
                    if (!this.sections[this.activeSection].data) return '';
                    return this.sections[this.activeSection].data[this.activeField] || '';
                },

                set activeValue(val) {
                    if (this.activeSection !== null && this.activeField) {
                        this.sections[this.activeSection].data[this.activeField] = val;

                        if (this.activeType === 'image' && this.activeElement) {
                            if (this.activeElement.tagName === 'IMG') {
                                this.activeElement.src = val;
                            } else {
                                this.activeElement.style.backgroundImage = `url("${val}")`;
                            }
                        }
                    }
                },

                updateElement() {
                    if (this.activeElement && this.activeType === 'text') {
                        this.activeElement.innerText = this.activeValue;
                    }
                },

                syncFromDOM(el) {
                    const sectionIndex = parseInt(el.closest('[data-section-index]').dataset.sectionIndex);
                    const field = el.dataset.field;
                    if (!isNaN(sectionIndex) && field) {
                        this.sections[sectionIndex].data[field] = el.innerText;
                    }
                },

                deselect() {
                    this.activeSection = null;
                    this.activeElement = null;
                },

                setPreview(width) {
                    this.previewWidth = width;
                },

                savePage() {
                    this.saving = true;

                    // Reconstruct sections based on DOM order
                    const newSections = [];
                    const sectionEls = document.querySelectorAll('#editor-canvas > [data-section-index]');

                    sectionEls.forEach(el => {
                        const originalIndex = parseInt(el.dataset.sectionIndex);
                        if (this.sections[originalIndex]) {
                            newSections.push(this.sections[originalIndex]);
                        }
                    });

                    this.page.content = { sections: newSections };

                    fetch(`/admin/editor/${this.page.slug}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: this.page.content })
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.saving = false;
                            if (data.success) {
                                alert('Page saved successfully!');
                            } else {
                                alert('Error saving page.');
                            }
                        })
                        .catch(err => {
                            this.saving = false;
                            console.error(err);
                            alert('Network error saving page.');
                        });
                },

                getNumber(val) {
                    return parseFloat(val) || 0;
                }
            });
        </script>
</body>

</html>