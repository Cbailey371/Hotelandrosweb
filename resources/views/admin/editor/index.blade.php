<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Editor - {{ $page->name }}</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;700;900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Montserrat:wght@300;400;700;900&family=Roboto:wght@300;400;700;900&family=Merriweather:wght@300;400;700;900&family=Oswald:wght@400;700&family=Lora:wght@400;700&family=Dancing+Script:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
            --dark-bg-color:
                {{ $settings['dark_mode_color'] ?? '#06070a' }}
            ;
        }

        @if(($settings['high_contrast'] ?? '0') == '1')
            :root {
                --contrast-level: 1;
            }

        @endif

        /* Dynamic Contrast Adjustments for Editor */
        .text-slate-600, .text-slate-500, .text-slate-700, .text-slate-800, p, span, h1, h2, h3, h4, h5, h6 {
            filter: contrast(calc(1 + var(--contrast-level))) brightness(calc(1 - var(--contrast-level) * 0.1));
        }

        /* Editor Specific Overrides */
        .cursor-wait {
            cursor: wait;
        }

        .draggable-offset {
            touch-action: none;
            user-select: none;
            cursor: move;
        }

        .draggable-element.selected,
        .draggable-offset.selected {
            outline: 2px solid #2563eb;
            /* Blue-600 */
            z-index: 50;
        }

        /* Visual Resize Handles */
        .draggable-element.selected::before,
        .draggable-element.selected::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: #2563eb;
            border: 1px solid white;
            border-radius: 50%;
            z-index: 51;
        }

        /* Top-Left and Bottom-Right */
        .draggable-element.selected::before {
            top: -4px;
            left: -4px;
        }

        .draggable-element.selected::after {
            bottom: -4px;
            right: -4px;
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
            class="fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shadow-sm"
            style="z-index: 1000 !important;">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="font-bold text-slate-800">Visual Editor <span class="text-blue-700 font-bold">/
                        {{ $page->name }}</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <button @click="savePage()"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg shadow-green-600/20 flex items-center gap-2 transition-all mr-4"
                    :class="{'opacity-75 cursor-wait': saving}">
                    <span class="material-symbols-outlined text-sm" x-show="!saving">save</span>
                    <span class="material-symbols-outlined text-sm animate-spin" x-show="saving">sync</span>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>

                <div class="flex bg-slate-100 rounded-lg p-1 border border-slate-200">
                    <a href="/lang/en"
                        class="px-3 py-2 rounded text-xs font-bold transition-all {{ app()->getLocale() == 'en' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:bg-white/50' }}"
                        title="English">EN</a>
                    <a href="/lang/es"
                        class="px-3 py-2 rounded text-xs font-bold transition-all {{ app()->getLocale() == 'es' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:bg-white/50' }}"
                        title="Español">ES</a>
                </div>
            </div>
        </header>

        <!-- LEFT SIDEBAR (Layers/Add) -->
        <aside class="w-16 bg-white border-r border-slate-200 pt-20 flex flex-col items-center gap-4 relative z-[150]">
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
                <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Layers</span>
                <button @click="showLayers = false" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>

            <div id="layers-list" class="flex-1 overflow-y-auto p-2 space-y-2">
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

                        <button @click.stop="removeSection(index)"
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
        <main class="flex-1 pt-20 pb-10 px-0 overflow-hidden flex justify-center bg-slate-100" @click="deselect()">
            <iframe id="editor-iframe" src="{{ route('admin.editor.preview', ['page' => $page->slug]) }}"
                class="w-full h-full bg-white shadow-2xl border-0 overflow-y-auto" title="Editor Preview">
            </iframe>
        </main>


        <!-- RIGHT SIDEBAR (Properties) -->
        <aside class="w-80 bg-white border-l border-slate-200 pt-20 px-6 overflow-auto relative z-[150] transition-all"
            x-show="activeElement || activeSection !== null" x-transition:enter="translate-x-full"
            x-transition:enter-end="translate-x-0">

            <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Properties</span>
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
                                class="text-xs font-bold text-blue-700 uppercase tracking-widest block mb-2">Typography</span>

                            <!-- Color -->
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-600">Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" x-model="activeStyleColor"
                                        class="w-8 h-8 rounded cursor-pointer border-0 p-0">
                                </div>
                            </div>

                            <!-- Font Size -->
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Size</label>
                                <div class="flex gap-2">
                                    <input type="range" x-model="activeStyleSizeVal"
                                        @input="updateStyle('fontSize', $el.value + activeStyleSizeUnit)" min="8"
                                        max="160" class="flex-1 accent-blue-600">
                                    <div class="flex items-center gap-1">
                                        <input type="number" x-model="activeStyleSizeVal"
                                            @input="updateStyle('fontSize', $el.value + activeStyleSizeUnit)"
                                            class="w-12 text-xs border-slate-200 rounded p-1 text-center">
                                        <select x-model="activeStyleSizeUnit"
                                            @change="updateStyle('fontSize', activeStyleSizeVal + $el.value)"
                                            class="text-[10px] border-slate-200 rounded p-1 bg-slate-50">
                                            <option value="px">px</option>
                                            <option value="rem">rem</option>
                                            <option value="em">em</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Font Family -->
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Font
                                    Family</label>
                                <select x-model="activeStyleFont"
                                    class="w-full text-xs border-slate-200 rounded p-2 text-slate-600 font-medium">
                                    <option value="inherit">Default (Plus Jakarta)</option>
                                    <optgroup label="Sans Serif">
                                        <option value="'Inter', sans-serif">Inter</option>
                                        <option value="'Montserrat', sans-serif">Montserrat</option>
                                        <option value="'Roboto', sans-serif">Roboto</option>
                                        <option value="'Oswald', sans-serif">Oswald (Strong)</option>
                                    </optgroup>
                                    <optgroup label="Serif & Elegant">
                                        <option value="'Playfair Display', serif">Playfair Display</option>
                                        <option value="'Merriweather', serif">Merriweather</option>
                                        <option value="'Lora', serif">Lora</option>
                                    </optgroup>
                                    <optgroup label="Accent">
                                        <option value="'Dancing Script', cursive">Dancing Script</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Font Weight & Decoration -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Weight</label>
                                    <select x-model="activeStyleWeight"
                                        class="w-full text-xs border-slate-200 rounded p-1 text-slate-600">
                                        <option value="300">Thin</option>
                                        <option value="400">Regular</option>
                                        <option value="500">Medium</option>
                                        <option value="600">Semi Bold</option>
                                        <option value="700">Bold</option>
                                        <option value="800">Extra Bold</option>
                                        <option value="900">Black</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Letter
                                        Spacing</label>
                                    <input type="number" x-model="activeStyleSpacing" step="0.5"
                                        class="w-full text-xs border-slate-200 rounded p-1">
                                </div>
                            </div>

                            <!-- Line Height -->
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Line
                                    Height</label>
                                <input type="range" x-model="activeStyleLineHeight" min="0.8" max="3" step="0.1"
                                    class="w-full accent-blue-600">
                            </div>

                            <!-- Text Align -->
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Alignment</label>
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

                            <!-- Advanced Positioning Offsets -->
                            <div class="border-t border-slate-100 pt-4 space-y-4">
                                <span
                                    class="text-[10px] font-bold text-blue-700 uppercase tracking-widest block mb-2">Advanced
                                    Offsets (Move)</span>

                                <div class="space-y-3">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 block flex justify-between">
                                            <span>Horizontal Offset (X)</span>
                                            <span x-text="activeOffsetX + 'px'"
                                                class="text-slate-400 font-normal"></span>
                                        </label>
                                        <input type="range" x-model="activeOffsetX" @input="updateOffset()" min="-200"
                                            max="200" class="w-full accent-blue-600">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 block flex justify-between">
                                            <span>Vertical Offset (Y)</span>
                                            <span x-text="activeOffsetY + 'px'"
                                                class="text-slate-400 font-normal"></span>
                                        </label>
                                        <input type="range" x-model="activeOffsetY" @input="updateOffset()" min="-200"
                                            max="200" class="w-full accent-blue-600">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 block">Margin Top</label>
                                        <input type="number" x-model="activeStyleMarginTop"
                                            class="w-full text-xs border-slate-200 rounded p-1">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 block">Margin Bottom</label>
                                        <input type="number" x-model="activeStyleMarginBottom"
                                            class="w-full text-xs border-slate-200 rounded p-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Icon Picker (for Cafe Features) -->
                    <div x-show="activeElement && activeElementLabel === 'Icono Café'"
                        class="border-t border-slate-100 pt-4 mt-4">
                        <h4 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3">Icono</h4>
                        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <div
                                class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600">
                                <span class="material-symbols-outlined text-3xl" x-text="getCafeFeatureIcon()"></span>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Material Symbol</p>
                                <button @click="showIconModal = true"
                                    class="w-full py-1.5 bg-white border border-slate-200 rounded text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-sm">grid_view</span> Seleccionar
                                </button>
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

                    <!-- Free Element Positioning (New) -->
                    <div x-show="activeElement && elementObjId !== null" class="border-t border-slate-100 pt-4 mt-4">
                        <h4 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3">Position & Size</h4>

                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 block">X (%)</label>
                                <input type="number" x-model="activePos.x" @input="updateElementPosition()"
                                    class="w-full text-xs border-slate-200 rounded p-1">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 block">Y (%)</label>
                                <input type="number" x-model="activePos.y" @input="updateElementPosition()"
                                    class="w-full text-xs border-slate-200 rounded p-1">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 block">W (px)</label>
                                <input type="number" x-model="activePos.w" @input="updateElementPosition()"
                                    class="w-full text-xs border-slate-200 rounded p-1">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 block">H (px)</label>
                                <input type="number" x-model="activePos.h" @input="updateElementPosition()"
                                    class="w-full text-xs border-slate-200 rounded p-1">
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button @click="changeLayer('up')"
                                class="flex-1 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded text-xs flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[10px]">arrow_upward</span> Layer Up
                            </button>
                            <button @click="changeLayer('down')"
                                class="flex-1 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded text-xs flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[10px]">arrow_downward</span> Layer Down
                            </button>
                        </div>

                        <button @click="deleteActiveElement()"
                            class="w-full mt-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded text-xs font-bold flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">delete</span> Delete Element
                        </button>
                    </div>
                </div>
            </div>

            <template x-if="!activeElement && activeSection !== null && sections[activeSection]">
                <div>
                    <h3 class="font-bold text-lg mb-4">Section Settings</h3>
                    <p class="text-sm text-slate-500">Select an element to edit properties.</p>

                    <div class="mt-4 space-y-6">
                        <!-- Spacing -->
                        <div>
                            <h4 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3">Spacing & Layout
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
                                class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3 border-t border-slate-100 pt-3">
                                Background</h4>

                            <!-- Overlay Opacity -->
                            <div class="mb-3">
                                <label class="text-xs font-bold text-slate-500 mb-1 block flex justify-between">
                                    <span>Overlay Opacity</span>
                                    <span x-text="(sections[activeSection]?.data?.overlay_opacity || 0) + '%'"
                                        class="font-normal text-slate-400"></span>
                                </label>
                                <input type="range" x-model="sections[activeSection].data.overlay_opacity" min="0"
                                    max="90" class="w-full accent-blue-600">
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

                            <!-- Background Image Picker -->
                            <div class="mt-4">
                                <label class="text-xs font-bold text-slate-500 mb-2 block">Background Image</label>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-10 bg-slate-100 rounded border border-slate-200 overflow-hidden">
                                        <img :src="sections[activeSection]?.data?.bg_image || '/images/branding/hero.png'"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <button @click="selectBackground()"
                                        class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                                        Change Image
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links (Only for Footer) -->
                        <div x-show="sections[activeSection]?.type === 'footer'" class="mt-6">
                            <h4
                                class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3 border-t border-slate-100 pt-3">
                                Redes Sociales</h4>

                            <!-- Facebook -->
                            <div class="mb-3">
                                <label class="text-xs font-bold text-slate-500 mb-1 block">Facebook URL</label>
                                <input type="text" x-model="sections[activeSection].data.social_facebook"
                                    class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-300"
                                    placeholder="https://facebook.com/...">
                            </div>

                            <!-- Instagram -->
                            <div class="mb-3">
                                <label class="text-xs font-bold text-slate-500 mb-1 block">Instagram URL</label>
                                <input type="text" x-model="sections[activeSection].data.social_instagram"
                                    class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-300"
                                    placeholder="https://instagram.com/...">
                            </div>

                            <!-- TripAdvisor -->
                            <div class="mb-3">
                                <label class="text-xs font-bold text-slate-500 mb-1 block">TripAdvisor URL</label>
                                <input type="text" x-model="sections[activeSection].data.social_tripadvisor"
                                    class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-300"
                                    placeholder="https://tripadvisor.com/...">
                            </div>

                            <!-- Icon Size Setting -->
                            <div class="mb-5 mt-4 border-t border-slate-100 pt-3">
                                <label
                                    class="text-[10px] font-bold text-blue-700 uppercase tracking-wider mb-2 flex justify-between">
                                    <span>Tamaño del Icono</span>
                                    <span x-text="(sections[activeSection]?.data?.social_icon_size || 24) + 'px'"
                                        class="font-normal text-slate-400"></span>
                                </label>
                                <input type="range" x-model="sections[activeSection].data.social_icon_size" min="16"
                                    max="48" step="2" class="w-full accent-blue-600">
                            </div>

                            <!-- Dynamic Custom Links Repeater -->
                            <div class="mt-6 border-t border-slate-100 pt-3">
                                <label class="text-xs font-bold text-slate-500 mb-3 flex justify-between items-center">
                                    <span>Otras Redes (Dinámico)</span>
                                    <button type="button" @click="
                                            let current = sections[activeSection].data.custom_social_links || [];
                                            sections[activeSection].data.custom_social_links = [...current, {name: '', url: '', icon: 'fa-brands fa-x-twitter'}];
                                        "
                                        class="text-xs text-blue-600 hover:text-blue-800 flex items-center gap-1 font-bold">
                                        <span class="material-symbols-outlined text-sm">add</span> Añadir Red
                                    </button>
                                </label>

                                <template
                                    x-if="sections[activeSection]?.data?.custom_social_links && sections[activeSection]?.data?.custom_social_links?.length > 0">
                                    <div class="space-y-4">
                                        <template
                                            x-for="(link, index) in (sections[activeSection]?.data?.custom_social_links || [])"
                                            :key="index">
                                            <div class="bg-slate-50 p-3 rounded border border-slate-200 relative mb-3">
                                                <button type="button"
                                                    @click="sections[activeSection].data.custom_social_links = sections[activeSection].data.custom_social_links.filter((_, i) => i !== index)"
                                                    class="absolute -top-2 -right-2 w-5 h-5 bg-red-100 hover:bg-red-500 text-red-600 hover:text-white rounded-full flex items-center justify-center transition-colors border border-red-200 hover:border-red-500 z-10"
                                                    title="Eliminar Red">
                                                    <span class="material-symbols-outlined"
                                                        style="font-size: 14px;">close</span>
                                                </button>

                                                <div class="mb-2">
                                                    <input type="text"
                                                        x-model="sections[activeSection].data.custom_social_links[index].name"
                                                        class="w-full bg-white border border-slate-200 rounded px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 outline-none"
                                                        placeholder="Nombre (ej. TikTok)">
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text"
                                                        x-model="sections[activeSection].data.custom_social_links[index].url"
                                                        class="w-full bg-white border border-slate-200 rounded px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 outline-none"
                                                        placeholder="URL (ej. https://tiktok.com/...)">
                                                </div>
                                                <div>
                                                    <input type="text"
                                                        x-model="sections[activeSection].data.custom_social_links[index].icon"
                                                        class="w-full bg-white border border-slate-200 rounded px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 outline-none"
                                                        placeholder="Clase Icono (ej. fa-brands fa-tiktok)">
                                                    <p class="text-[9px] text-slate-400 mt-1">Soporta FontAwesome
                                                        (fa-brands fa-twitter, etc.)</p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template
                                    x-if="!sections[activeSection]?.data?.custom_social_links || sections[activeSection].data.custom_social_links.length === 0">
                                    <p class="text-[10px] text-slate-400 text-center py-2 italic">Sin redes adicionales.
                                    </p>
                                </template>
                            </div>

                            <p class="text-[10px] text-slate-400 mt-4 leading-relaxed">Déjalo en blanco o usa '#' para
                                ocultar el icono en la página pública.</p>
                        </div>
                    </div>
                </div>
            </template>

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

                <div class="p-8 overflow-y-auto min-h-[400px]">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <!-- Upload Placeholder -->
                        <div @click="$refs.fileInput.click()"
                            class="relative group aspect-square bg-slate-50 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-[2rem] flex flex-col items-center justify-center text-slate-400 hover:border-primary/50 hover:bg-slate-100/50 cursor-pointer transition-all shadow-sm">
                            <span
                                class="material-symbols-outlined text-4xl mb-2 text-slate-300 group-hover:text-primary transition-colors">cloud_upload</span>
                            <span
                                class="text-[10px] font-black uppercase tracking-widest group-hover:text-primary transition-colors text-center px-4">Upload
                                New</span>

                            <input type="file" x-ref="fileInput" class="hidden" accept="image/*"
                                @change="handleFileUpload($event)">

                            <div x-show="uploading"
                                class="absolute inset-0 bg-white/90 dark:bg-slate-900/90 flex items-center justify-center z-10 rounded-[2rem]">
                                <span class="material-symbols-outlined animate-spin text-primary text-3xl">sync</span>
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        @foreach($galleryImages as $img)
                            <div @click="selectImage('{{ asset($img->image_path) }}', {{ $img->id }})"
                                class="relative group aspect-square rounded-[2rem] overflow-hidden border-2 border-slate-100 dark:border-slate-800 transition-all shadow-sm bg-white dark:bg-slate-900 cursor-pointer hover:ring-4 ring-primary/20 hover:shadow-xl">
                                <img src="{{ asset($img->image_path) }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                <!-- Overlay Tooltip/Badge -->
                                <div
                                    class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <div
                                        class="bg-white/90 text-primary p-2 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                        <span class="material-symbols-outlined text-2xl">check</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Icon Selection Modal -->
        <div x-show="showIconModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak
            style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showIconModal = false"></div>

            <div
                class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl relative z-10 overflow-hidden animate-fade-in-up flex flex-col max-h-[80vh]">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <div>
                        <h3 class="font-black text-xl text-slate-800">Seleccionar Icono</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Material Symbols
                            Library</p>
                    </div>
                    <button @click="showIconModal = false"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="p-8 flex flex-col overflow-hidden">
                    <div class="relative mb-6">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input type="text" x-model="iconSearch" placeholder="Buscar icono (ej: bar, cafe, food...)"
                            class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-4 overflow-y-auto p-2 scrollbar-hide">
                        <template x-for="icon in filteredIcons" :key="icon">
                            <button @click="selectIcon(icon)"
                                class="flex flex-col items-center justify-center p-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 transition-all group"
                                :class="{'bg-blue-50 text-blue-600 ring-2 ring-blue-500': getCafeFeatureIcon() === icon}">
                                <span
                                    class="material-symbols-outlined text-3xl mb-1 group-hover:scale-125 transition-transform"
                                    x-text="icon"></span>
                                <span class="text-[8px] uppercase font-bold truncate w-full text-center"
                                    x-text="icon.replace('_', ' ')"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alpine Editor Logic -->

        <script>
            window.setupEditor = (initialPage) => ({
                page: @json($page),
                sections: @json($page->content['sections'] ?? []),
                carouselImages: @json($carouselImages ?? []), // Active in carousel
                galleryImages: @json($galleryImages ?? []),   // All uploaded images
                attractions: @json($attractions ?? []),       // Local Attractions
                showGalleryModal: false, // Legacy, can remove later or keep for future
                showImageModal: false,   // Shared Image Picker
                imageModalContext: 'element', // 'element' | 'carousel'
                saving: false,
                activeSection: null,
                activeElement: null,
                activeElementLabel: 'Element',
                activeType: 'text',
                activeField: null,
                activePos: { x: 0, y: 0, w: 0, h: 0 }, // For free elements
                previewWidth: '100%',
                showAddModal: false,
                showLayers: false,
                showImageModal: false,
                uploading: false,
                activeImageTarget: null, // 'element' or 'background'
                activeStyleSizeUnit: 'px',
                activeStyleSizeVal: 16,
                activeOffsetX: 0,
                activeOffsetY: 0,

                // Icon Picker state
                showIconModal: false,
                iconSearch: '',
                allIcons: [
                    'local_cafe', 'restaurant', 'local_bar', 'wifi', 'pool', 'spa', 'icecream',
                    'bakery_dining', 'cake', 'coffee', 'wine_bar', 'liquor', 'dinner_dining',
                    'lunch_dining', 'breakfast_dining', 'fitness_center', 'ac_unit', 'tv',
                    'luggage', 'shower', 'bed', 'bathtub', 'apartment', 'deck', 'hotel',
                    'room_service', 'cleaning_services', 'laundry', 'local_parking',
                    'directions_car', 'airport_shuttle', 'map', 'explore', 'history',
                    'event', 'calendar_month', 'beach_access', 'umbrella',
                    'celebration', 'theater_comedy', 'nightlife', 'hiking', 'sailing'
                ],

                get filteredIcons() {
                    if (!this.iconSearch) return this.allIcons;
                    return this.allIcons.filter(icon => icon.toLowerCase().includes(this.iconSearch.toLowerCase()));
                },

                getCafeFeatureIcon() {
                    if (!this.activeField || !this.activeField.startsWith('cafe_feature_')) return 'local_cafe';
                    const featureId = this.activeField.replace('cafe_feature_', '');
                    const feature = this.sections[this.activeSection].data.features.find(f => f.id === featureId);
                    return feature ? feature.icon : 'local_cafe';
                },

                selectIcon(iconName) {
                    if (!this.activeField || !this.activeField.startsWith('cafe_feature_')) return;
                    const featureId = this.activeField.replace('cafe_feature_', '');
                    const features = this.sections[this.activeSection].data.features;
                    const fIndex = features.findIndex(f => f.id === featureId);
                    if (fIndex > -1) {
                        this.sections[this.activeSection].data.features[fIndex].icon = iconName;
                        this.showIconModal = false;
                    }
                },

                // Default data for new sections
                componentDefaults: {
                    'hero': { title_es: 'Nuevo Hero', title_en: 'New Hero', bg_image: '/images/branding/hero.png', gap: 24, overlay_opacity: 50, overlay_color: '#000000' },
                    'rooms': { title_es: 'Habitaciones', title_en: 'Rooms' },
                    'cafe': {
                        title_es: 'Sabores Artesanales & Coctelería',
                        title_en: 'Artisan Flavors & Cocktails',
                        bg_image: '/images/gallery/bar.png',
                        features: [
                            { id: 'f1', icon: 'local_cafe', label_es: 'Café de Especialidad', label_en: 'Specialty Coffee' },
                            { id: 'f2', icon: 'bakery_dining', label_es: 'Repostería Artesanal', label_en: 'Artisan Bakery' },
                            { id: 'f3', icon: 'cocktail', label_es: 'Coctelería de Autor', label_en: 'Signature Cocktails' }
                        ]
                    },
                    'gallery': { title_es: 'Galería', title_en: 'Gallery' },
                    'location': { title_es: 'Ubicación', title_en: 'Location' },
                    'attractions': { title_es: 'Atracciones', title_en: 'Attractions' }
                },

                removeSection(index) {
                    if (!confirm('¿Estás seguro que deseas eliminar esta sección?')) return;
                    this.sections.splice(index, 1);

                    const iframeDoc = document.getElementById('editor-iframe').contentDocument;
                    if (iframeDoc) {
                        const rows = iframeDoc.querySelectorAll('#canvas-content > .section-wrapper');
                        if (rows[index]) rows[index].remove();

                        this.$nextTick(() => {
                            const newRows = iframeDoc.querySelectorAll('#canvas-content > .section-wrapper');
                            newRows.forEach((el, idx) => {
                                el.setAttribute('data-section-index', idx);
                                // Update indices in buttons inside the toolbar
                                el.querySelectorAll('.action-btn').forEach(btn => btn.setAttribute('data-index', idx));
                                el.querySelectorAll('[data-section-index]').forEach(sub => sub.setAttribute('data-section-index', idx));
                            });
                        });
                    }

                    if (this.activeSection === index) this.deselect();
                    else if (this.activeSection > index) this.activeSection--;
                },
                moveSectionUp(index) {
                    if (index <= 0) return;
                    const item = this.sections.splice(index, 1)[0];
                    this.sections.splice(index - 1, 0, item);

                    const iframeDoc = document.getElementById('editor-iframe').contentDocument;
                    if (iframeDoc) {
                        const rows = iframeDoc.querySelectorAll('#canvas-content > .section-wrapper');
                        const currentEl = rows[index];
                        const prevEl = rows[index - 1];
                        if (currentEl && prevEl) {
                            currentEl.parentNode.insertBefore(currentEl, prevEl);
                        }

                        this.$nextTick(() => {
                            const newRows = iframeDoc.querySelectorAll('#canvas-content > .section-wrapper');
                            newRows.forEach((el, idx) => {
                                el.setAttribute('data-section-index', idx);
                                el.querySelectorAll('.action-btn').forEach(btn => btn.setAttribute('data-index', idx));
                            });
                        });
                    }

                    if (this.activeSection === index) this.activeSection = index - 1;
                    else if (this.activeSection === index - 1) this.activeSection = index;
                },
                moveSectionDown(index) {
                    if (index >= this.sections.length - 1) return;
                    const item = this.sections.splice(index, 1)[0];
                    this.sections.splice(index + 1, 0, item);

                    const iframeDoc = document.getElementById('editor-iframe').contentDocument;
                    if (iframeDoc) {
                        const rows = iframeDoc.querySelectorAll('#canvas-content > .section-wrapper');
                        const currentEl = rows[index];
                        const nextEl = rows[index + 1];
                        if (currentEl && nextEl) {
                            currentEl.parentNode.insertBefore(nextEl, currentEl);
                        }

                        this.$nextTick(() => {
                            const newRows = iframeDoc.querySelectorAll('#canvas-content > .section-wrapper');
                            newRows.forEach((el, idx) => {
                                el.setAttribute('data-section-index', idx);
                                el.querySelectorAll('.action-btn').forEach(btn => btn.setAttribute('data-index', idx));
                            });
                        });
                    }

                    if (this.activeSection === index) this.activeSection = index + 1;
                    else if (this.activeSection === index + 1) this.activeSection = index;
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
                        body: JSON.stringify({ type, data, index: newIndex })
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
                                // 3. Append to Iframe DOM wrapper
                                const iframeDoc = document.getElementById('editor-iframe').contentDocument;
                                const canvas = iframeDoc ? iframeDoc.getElementById('canvas-content') : null;

                                if (canvas) {
                                    const wrapper = iframeDoc.createElement('div');
                                    wrapper.className = "section-wrapper";
                                    wrapper.setAttribute('data-section-index', newIndex);

                                    wrapper.innerHTML = `
                                        <div class="section-toolbar">
                                            <button class="section-handle cursor-grab active:cursor-grabbing hover:text-blue-200 mt-0.5"><span class="material-symbols-outlined text-sm">drag_indicator</span></button>
                                            <span class="font-bold uppercase tracking-wider self-center">${type}</span>
                                            
                                            <div class="w-px h-4 bg-blue-400 mx-1 self-center"></div>
                                            
                                            <button class="action-btn hover:text-blue-200 flex items-center gap-1" data-action="add-text" data-index="${newIndex}" title="Add Text">
                                                <span class="material-symbols-outlined text-[14px]">title</span>
                                            </button>
                                            <button class="action-btn hover:text-blue-200 flex items-center gap-1" data-action="add-image" data-index="${newIndex}" title="Add Image">
                                                <span class="material-symbols-outlined text-[14px]">image</span>
                                            </button>
                                            
                                            <div class="w-px h-4 bg-blue-400 mx-1 self-center"></div>
                                            
                                            <button class="action-btn hover:text-blue-200" data-action="move-up" data-index="${newIndex}"><span class="material-symbols-outlined text-[10px]">arrow_upward</span></button>
                                            <button class="action-btn hover:text-blue-200" data-action="move-down" data-index="${newIndex}"><span class="material-symbols-outlined text-[10px]">arrow_downward</span></button>
                                            <button class="action-btn hover:text-red-200" data-action="delete" data-index="${newIndex}"><span class="material-symbols-outlined text-[10px]">delete</span></button>
                                        </div>
                                        ${response.html}
                                    `;

                                    canvas.appendChild(wrapper);

                                    // Let Alpine in the iframe process the new HTML
                                    if (iframeDoc.defaultView.Alpine) {
                                        iframeDoc.defaultView.Alpine.initTree(wrapper);
                                    }

                                    // Sync state so the new section gets its data
                                    this.syncStateToIframe();

                                    // Auto-select the new section
                                    this.selectSection(newIndex);

                                    // Dispatch ready event to restart libraries (like Swiper)
                                    iframeDoc.dispatchEvent(new CustomEvent('editor-preview-ready', { bubbles: true }));

                                    // Scroll to bottom
                                    wrapper.scrollIntoView({ behavior: 'smooth' });
                                }
                            }
                        });
                },

                syncStateToIframe() {
                    const iframe = document.getElementById('editor-iframe');
                    if (iframe && iframe.contentWindow) {
                        // Clone safe objects to avoid cloning Alpine's Proxies across window memory bounds
                        iframe.contentWindow.postMessage({
                            type: 'UPDATE_STATE',
                            sections: JSON.parse(JSON.stringify(this.sections)),
                            carouselImages: JSON.parse(JSON.stringify(this.carouselImages)),
                            galleryImages: JSON.parse(JSON.stringify(this.galleryImages)),
                            attractions: JSON.parse(JSON.stringify(this.attractions))
                        }, '*');
                    }
                },

                init() {
                    console.log('Editor Initialized', this);
                    console.log('Does selectSection exist?', typeof this.selectSection);

                    // Sync state to iframe when sections change
                    this.$watch('sections', (newValue, oldValue) => {
                        this.syncStateToIframe();
                    }, { deep: true });
                    this.$watch('carouselImages', () => this.syncStateToIframe(), { deep: true });
                    this.$watch('galleryImages', () => this.syncStateToIframe(), { deep: true });
                    this.$watch('attractions', () => this.syncStateToIframe(), { deep: true });

                    // -- CROSS-WINDOW COMMUNICATION FROM IFRAME --
                    window.addEventListener('message', (e) => {
                        if (!e.data || !e.data.type) return;

                        if (e.data.type === 'EDITOR_ACTION') {
                            const { action, index } = e.data;
                            if (action === 'add-text') this.addElement(index, 'text');
                            if (action === 'add-image') this.addElement(index, 'image');
                            if (action === 'move-up') this.moveSectionUp(index);
                            if (action === 'move-down') this.moveSectionDown(index);
                            if (action === 'delete') this.removeSection(index);
                        }

                        if (e.data.type === 'SELECT_SECTION') {
                            this.selectSection(e.data.index);
                        }

                        if (e.data.type === 'SELECT_ELEMENT') {
                            this.selectElementBySelector(e.data.selector, e.data.dataset, e.data.sectionIndex);
                        }

                        if (e.data.type === 'SYNC_INPUT') {
                            const { sectionIndex, field, elementId, content } = e.data;
                            if (field) {
                                this.sections[sectionIndex].data[field] = content;
                            } else if (elementId) {
                                const elIndex = this.sections[sectionIndex].data.elements.findIndex(el => el.id === elementId);
                                if (elIndex > -1) {
                                    this.sections[sectionIndex].data.elements[elIndex].content = content;
                                }
                            }
                        }

                        if (e.data.type === 'DATA_ACTION') {
                            const { action, id, field, value, context } = e.data;
                            if (action === 'deleteGalleryImage') {
                                this.deleteGalleryImage(id);
                            } else if (action === 'updateAttraction') {
                                this.updateAttraction(id, field, value);
                            } else if (action === 'openImageModal') {
                                this.imageModalContext = context;
                                this.showImageModal = true;
                            } else if (action === 'deleteCafeFeature') {
                                this.deleteCafeFeature(e.data.index, e.data.featureId);
                            } else if (action === 'addCafeFeature') {
                                this.addCafeFeature(e.data.index);
                            } else if (action === 'updateCafeFeature') {
                                this.updateCafeFeature(e.data.index, e.data.featureId, e.data.field, e.data.value);
                            } else if (action === 'addAttraction') {
                                this.addAttraction();
                            } else if (action === 'deleteAttraction') {
                                this.deleteAttraction(e.data.id);
                            }
                        }
                    });

                    // Initialize Sortable
                    // Initialize Sortable for Layers Panel
                    const layersList = document.getElementById('layers-list');
                    if (layersList && window.Sortable) {
                        new Sortable(layersList, {
                            animation: 150,
                            handle: '.section-handle-layer',
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                if (evt.oldIndex === evt.newIndex) return;

                                // 1. Move in state
                                const item = this.sections.splice(evt.oldIndex, 1)[0];
                                this.sections.splice(evt.newIndex, 0, item);

                                // 2. Move in Iframe DOM
                                const iframeDoc = document.getElementById('editor-iframe').contentDocument;
                                if (iframeDoc) {
                                    const container = iframeDoc.getElementById('canvas-content');
                                    const rows = Array.from(container.querySelectorAll('.section-wrapper'));
                                    const movedRow = rows.splice(evt.oldIndex, 1)[0];
                                    rows.splice(evt.newIndex, 0, movedRow);

                                    // Re-append in new order
                                    rows.forEach(row => container.appendChild(row));

                                    // Update indices
                                    this.$nextTick(() => {
                                        const newRows = container.querySelectorAll('.section-wrapper');
                                        newRows.forEach((el, idx) => {
                                            el.setAttribute('data-section-index', idx);
                                            el.querySelectorAll('.action-btn').forEach(btn => btn.setAttribute('data-index', idx));
                                            el.querySelectorAll('[data-section-index]').forEach(sub => sub.setAttribute('data-section-index', idx));
                                        });
                                    });
                                }
                            }
                        });
                    }


                    // Initialize Interact.js
                    this.initInteract();
                },

                initInteract() {
                    const self = this;
                    const iframe = document.getElementById('editor-iframe');
                    const iframeDoc = iframe ? iframe.contentDocument : null;

                    if (!iframeDoc) return;

                    // Interact for Draggables
                    interact('.draggable-element', { context: iframeDoc }).draggable({
                        listeners: {
                            start(event) {
                                console.log(event.type, event.target)
                            },
                            move(event) {
                                const target = event.target;

                                // Keep the dragged position in the data-x/data-y attributes
                                const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                                const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                                // Translate the element
                                target.style.transform = `translate(${x}px, ${y}px)`;

                                // Update the posiion attributes
                                target.setAttribute('data-x', x);
                                target.setAttribute('data-y', y);

                                // Update Vue/Alpine state if selected
                                if (self.activeElement === target) {
                                    // Convert to % for storage? 
                                    // For now just tracking px movement visually. 
                                    // Real save logic happens on end or manual update.
                                }
                            },
                            end(event) {
                                const target = event.target;
                                const sectionIndex = parseInt(target.closest('[data-section-index]').dataset.sectionIndex);
                                const elementId = target.dataset.elementId;

                                // Calculate position relative to container in %
                                const parent = target.parentElement;
                                const parentRect = parent.getBoundingClientRect();
                                const targetRect = target.getBoundingClientRect();

                                const relativeX = targetRect.left - parentRect.left;
                                const relativeY = targetRect.top - parentRect.top;

                                const xPercent = (relativeX / parentRect.width) * 100;
                                const yPercent = (relativeY / parentRect.height) * 100;

                                // Update Data
                                self.updateElementData(sectionIndex, elementId, {
                                    x: xPercent,
                                    y: yPercent
                                });

                                // Reset transform and use top/left for persistence
                                target.style.transform = 'none';
                                target.style.left = xPercent + '%';
                                target.style.top = yPercent + '%';
                                target.setAttribute('data-x', 0);
                                target.setAttribute('data-y', 0);

                                if (self.activeElement === target) {
                                    self.activePos.x = xPercent.toFixed(2);
                                    self.activePos.y = yPercent.toFixed(2);
                                }
                            }
                        },
                        modifiers: [
                            interact.modifiers.restrictRect({
                                restriction: 'parent',
                                endOnly: true
                            })
                        ]
                    })
                        .resizable({
                            edges: { left: true, right: true, bottom: true, top: true },
                            listeners: {
                                move: function (event) {
                                    let { x, y } = event.target.dataset;

                                    x = (parseFloat(x) || 0) + event.deltaRect.left;
                                    y = (parseFloat(y) || 0) + event.deltaRect.top;

                                    Object.assign(event.target.style, {
                                        width: `${event.rect.width}px`,
                                        height: `${event.rect.height}px`,
                                        transform: `translate(${x}px, ${y}px)`
                                    });

                                    Object.assign(event.target.dataset, { x, y });
                                },
                                end: function (event) {
                                    const target = event.target;
                                    const sectionIndex = parseInt(target.closest('[data-section-index]').dataset.sectionIndex);
                                    const elementId = target.dataset.elementId;

                                    // Update Data
                                    self.updateElementData(sectionIndex, elementId, {
                                        width: event.rect.width,
                                        height: event.rect.height
                                    });

                                    if (self.activeElement === target) {
                                        self.activePos.w = event.rect.width;
                                        self.activePos.h = event.rect.height;
                                    }
                                }
                            }
                        });


                    // Interact for Offset Draggables (Static fields like Cafe icons)
                    interact('.draggable-offset', { context: iframeDoc }).draggable({
                        ignoreFrom: '[contenteditable="true"]',
                        listeners: {
                            start(event) {
                                // Auto-select handled by iframe
                            },
                            move(event) {
                                const target = event.target;
                                if (self.activeElement === target) {
                                    self.activeOffsetX += event.dx;
                                    self.activeOffsetY += event.dy;
                                    self.updateOffset();
                                }
                            }
                        }
                    });
                },

                addElement(sectionIndex, type) {
                    if (!this.sections[sectionIndex].data.elements) {
                        this.sections[sectionIndex].data.elements = [];
                    }

                    const newElement = {
                        id: 'el_' + Date.now(),
                        type: type,
                        content: type === 'text' ? 'New Text' : '/images/branding/hero.png',
                        x: 50, // Center
                        y: 50,
                        width: type === 'text' ? 200 : 300,
                        height: type === 'text' ? 40 : 200,
                        styles: {
                            color: type === 'text' ? '#000000' : 'transparent',
                            fontSize: '16px',
                            fontFamily: 'inherit',
                            fontWeight: '400',
                            letterSpacing: '0px',
                            lineHeight: '1.2',
                            textAlign: 'left',
                            marginTop: '0px',
                            marginBottom: '0px',
                            translateX: 0,
                            translateY: 0,
                            zIndex: 10
                        }
                    };

                    this.sections[sectionIndex].data.elements.push(newElement);

                    // Wait for DOM update then init interact? (Interact acts on selector, so it should pick it up automatically or we re-run)
                    this.$nextTick(() => {
                        // Just to be safe, maybe re-run interact init?
                    });
                },

                updateElementData(sectionIndex, elementId, updates) {
                    const elements = this.sections[sectionIndex].data.elements;
                    const elIndex = elements.findIndex(e => e.id === elementId);
                    if (elIndex > -1) {
                        this.sections[sectionIndex].data.elements[elIndex] = { ...elements[elIndex], ...updates };
                    }
                },

                updateElementPosition() {
                    // Manual input update
                    if (this.activeElementSelector && this.elementObjId) {
                        const sectionIndex = this.activeSection;
                        const elementId = this.elementObjId;

                        // 1. Update Data Model
                        this.updateElementData(sectionIndex, elementId, {
                            x: parseFloat(this.activePos.x),
                            y: parseFloat(this.activePos.y),
                            width: parseFloat(this.activePos.w),
                            height: parseFloat(this.activePos.h)
                        });

                        // 2. Update Iframe DOM
                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin && iframeWin.updateElementStyle) {
                            iframeWin.updateElementStyle(this.activeElementSelector, 'left', this.activePos.x + '%');
                            iframeWin.updateElementStyle(this.activeElementSelector, 'top', this.activePos.y + '%');
                            iframeWin.updateElementStyle(this.activeElementSelector, 'width', this.activePos.w + 'px');
                            iframeWin.updateElementStyle(this.activeElementSelector, 'height', this.activePos.h + 'px');
                        }
                    }
                },

                deleteActiveElement() {
                    if (confirm('Are you sure?')) {
                        if (this.elementObjId && this.activeSection !== null) {
                            this.sections[this.activeSection].data.elements = this.sections[this.activeSection].data.elements.filter(e => e.id !== this.elementObjId);
                            this.deselect();
                        }
                    }
                },

                changeLayer(dir) {
                    if (this.activeElementSelector && this.elementObjId) {
                        const sectionIndex = this.activeSection;
                        const elementId = this.elementObjId;
                        const elements = this.sections[sectionIndex].data.elements;
                        const elIndex = elements.findIndex(e => e.id === elementId);

                        let currentZ = elements[elIndex].styles.zIndex || 10;
                        if (dir === 'up') currentZ++;
                        else currentZ--;

                        this.sections[sectionIndex].data.elements[elIndex].styles.zIndex = currentZ;

                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin && iframeWin.updateElementStyle) {
                            iframeWin.updateElementStyle(this.activeElementSelector, 'zIndex', currentZ);
                        }
                    }
                },

                selectSection(index) {
                    this.activeSection = index;
                    this.activeElement = null;

                    // 1. Scroll into view inside Iframe
                    const iframe = document.getElementById('editor-iframe');
                    const iframeDoc = iframe.contentDocument;
                    const iframeWin = iframe.contentWindow;

                    if (iframeDoc) {
                        const el = iframeDoc.querySelector(`.section-wrapper[data-section-index="${index}"]`);
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }

                    // 2. Visual activation (if helper exists)
                    if (iframeWin && iframeWin.activateSection) {
                        iframeWin.activateSection(index);
                    }
                },

                selectElementBySelector(selector, dataset, sectionIndex) {
                    if (!selector) return;

                    this.activeElementSelector = selector;  // Store string instead of node
                    this.activeElementLabel = dataset.label || 'Element';
                    this.activeType = dataset.type || 'text';
                    this.activeField = dataset.field || null;
                    this.elementObjId = dataset.elementId || null;

                    if (sectionIndex !== null) {
                        this.activeSection = sectionIndex;
                    }

                    // Retrieve text value & set activeElement node
                    const iframeWin = document.getElementById('editor-iframe').contentWindow;
                    if (iframeWin && iframeWin.document) {
                        const target = iframeWin.document.querySelector(selector);
                        if (target) {
                            this.activeElement = target; // Store node for interact.js compatibility
                            if (this.activeType === 'text') {
                                this.activeValue = target.innerText.trim();
                            }
                        } else {
                            this.activeElement = true; // Fallback to boolean to ensure panel stays open
                        }
                    } else {
                        this.activeElement = true;
                    }

                    // Reset common states
                    this.activeStyleSizeVal = 16;
                    this.activeStyleSizeUnit = 'px';
                    this.activeOffsetX = 0;
                    this.activeOffsetY = 0;

                    // Populate data for elements
                    // 1. Font Size
                    const rawSize = this.getStyleValue('fontSize', '16px');
                    const match = rawSize.match(/^([\d.]+)(\w+)$/);
                    if (match) {
                        this.activeStyleSizeVal = parseFloat(match[1]);
                        this.activeStyleSizeUnit = match[2];
                    }

                    // 2. Offsets
                    this.activeOffsetX = parseInt(this.getStyleValue('translateX', 0));
                    this.activeOffsetY = parseInt(this.getStyleValue('translateY', 0));

                    // 3. Dynamic Element specific logic (Position/Size)
                    if (this.elementObjId) {
                        const element = this.sections[this.activeSection].data.elements?.find(e => e.id === this.elementObjId);
                        if (element) {
                            this.activePos = {
                                x: parseFloat(element.x),
                                y: parseFloat(element.y),
                                w: parseFloat(element.width),
                                h: parseFloat(element.height)
                            };
                        }

                        // Highlight Logic inside Iframe
                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin) {
                            iframeWin.document.querySelectorAll('.draggable-element').forEach(e => e.classList.remove('selected'));
                            const target = iframeWin.document.querySelector(selector);
                            if (target) target.classList.add('selected');
                        }
                    } else {
                        // Static Field highlight removal
                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin) {
                            iframeWin.document.querySelectorAll('.draggable-element').forEach(e => e.classList.remove('selected'));
                        }
                    }
                },

                getOtherField(field) {
                    if (!field) return null;
                    if (field.endsWith('_es')) return field.replace('_es', '_en');
                    if (field.endsWith('_en')) return field.replace('_en', '_es');
                    return null;
                },

                getStyleValue(prop, defaultVal = '') {
                    if (this.activeSection === null || !this.activeElement) return defaultVal;

                    // CASE 1: Dynamic Element (Draggable)
                    if (this.elementObjId) {
                        const element = this.sections[this.activeSection].data.elements?.find(e => e.id === this.elementObjId);
                        if (element && element.styles) {
                            return element.styles[prop] || defaultVal;
                        }
                        return defaultVal;
                    }

                    // CASE 2: Static Field (Title, Subtitle, etc. or Collection Item)
                    if (!this.activeField) return defaultVal;

                    // Try current locale or direct field access (for collection items)
                    let val = this.sections[this.activeSection].data[this.activeField + '_' + prop];

                    // Try fallback locale if empty and it's a locale-based field
                    if (!val && (this.activeField.endsWith('_es') || this.activeField.endsWith('_en'))) {
                        const otherField = this.getOtherField(this.activeField);
                        if (otherField) {
                            val = this.sections[this.activeSection].data[otherField + '_' + prop];
                        }
                    }
                    return val || defaultVal;
                },

                get activeStyleColor() {
                    return this.getStyleValue('color', '#000000');
                },
                set activeStyleColor(val) { this.updateStyle('color', val); },

                get activeStyleSize() {
                    return this.getStyleValue('fontSize', '');
                },
                set activeStyleSize(val) { /* Handled by complex input logic, leaving empty or specific handler */ },

                get activeStyleFont() {
                    return this.getStyleValue('fontFamily', '');
                },
                set activeStyleFont(val) {
                    console.log('SET activeStyleFont:', val);
                    this.updateStyle('fontFamily', val);
                },

                get activeStyleAlign() {
                    return this.getStyleValue('textAlign', 'left');
                },
                set activeStyleAlign(val) { this.updateStyle('textAlign', val); },

                get activeStyleWeight() {
                    return this.getStyleValue('fontWeight', '400');
                },
                set activeStyleWeight(val) { this.updateStyle('fontWeight', val); },

                get activeStyleSpacing() {
                    return parseFloat(this.getStyleValue('letterSpacing', '0'));
                },
                set activeStyleSpacing(val) { this.updateStyle('letterSpacing', val + 'px'); },

                get activeStyleLineHeight() {
                    return this.getStyleValue('lineHeight', '1.2');
                },
                set activeStyleLineHeight(val) { this.updateStyle('lineHeight', val); },

                get activeStyleMarginTop() {
                    return parseFloat(this.getStyleValue('marginTop', '0'));
                },
                set activeStyleMarginTop(val) { this.updateStyle('marginTop', val + 'px'); },

                get activeStyleMarginBottom() {
                    return parseFloat(this.getStyleValue('marginBottom', '0'));
                },
                set activeStyleMarginBottom(val) { this.updateStyle('marginBottom', val + 'px'); },

                updateStyle(prop, val) {
                    if (this.activeSection !== null && this.activeElementSelector) {
                        console.log('Updating style:', prop, val, 'for selector:', this.activeElementSelector);

                        // 1. Update Data Model
                        if (this.elementObjId) {
                            // Dynamic Element
                            const elements = this.sections[this.activeSection].data.elements;
                            const elIndex = elements.findIndex(e => e.id === this.elementObjId);
                            if (elIndex > -1) {
                                if (!this.sections[this.activeSection].data.elements[elIndex].styles) {
                                    this.sections[this.activeSection].data.elements[elIndex].styles = {};
                                }
                                this.sections[this.activeSection].data.elements[elIndex].styles[prop] = val;
                            }
                        } else if (this.activeField) {
                            // Static Field or Collection Item
                            this.sections[this.activeSection].data[this.activeField + '_' + prop] = val;
                        }

                        // 2. Update Canvas DOM dynamically using Iframe Helper
                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin && iframeWin.updateElementStyle) {
                            iframeWin.updateElementStyle(this.activeElementSelector, prop, val);
                        }
                    }
                },

                updateOffset() {
                    if (this.activeSection !== null && this.activeElementSelector) {
                        const x = this.activeOffsetX;
                        const y = this.activeOffsetY;

                        if (this.elementObjId) {
                            // Dynamic elements use internal state + transform
                            const elements = this.sections[this.activeSection].data.elements;
                            const elIndex = elements.findIndex(e => e.id === this.elementObjId);
                            if (elIndex > -1) {
                                this.sections[this.activeSection].data.elements[elIndex].translateX = x;
                                this.sections[this.activeSection].data.elements[elIndex].translateY = y;
                            }
                        } else if (this.activeField) {
                            // Static fields or Collection Items save with prefix
                            this.sections[this.activeSection].data[this.activeField + '_translateX'] = x;
                            this.sections[this.activeSection].data[this.activeField + '_translateY'] = y;
                        }

                        // 2. Update Canvas DOM dynamically using Iframe Helper
                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin && iframeWin.updateElementStyle) {
                            iframeWin.updateElementStyle(this.activeElementSelector, 'transform', `translate(${x}px, ${y}px)`);
                        }
                    }
                },

                selectBackground() {
                    this.activeImageTarget = 'background';
                    this.showImageModal = true;
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    // Context Check: If for Carousel, use the specific uploader
                    if (this.imageModalContext === 'carousel') {
                        this.uploadGalleryImage(event);
                        // Optional: Keep modal open? Or close?
                        // uploadGalleryImage alerts on success.
                        // Let's close modal to match behavior
                        this.showImageModal = false;
                        return;
                    }

                    this.uploading = true;
                    const formData = new FormData();
                    formData.append('image', file);

                    fetch('/admin/editor/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.uploading = false;
                            if (data.success) {
                                this.selectImage(data.url);
                            } else {
                                alert('Upload failed: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(err => {
                            this.uploading = false;
                            console.error('Upload error:', err);
                            alert('Error uploading image');
                        });
                },

                selectImage(url, id = null) {
                    // Check Context
                    if (this.imageModalContext === 'carousel') {
                        if (id) {
                            this.addToCarousel(id);
                        } else {
                            // If no ID (e.g. from upload), we might need to handle it or error
                            // But handleFileUpload for carousel context should have handled it already
                            console.warn('Carousel selectImage called without ID', url);
                        }
                        this.showImageModal = false;
                        this.activeImageTarget = null;
                        return;
                    }

                    if (this.imageModalContext && this.imageModalContext.startsWith('attraction:')) {
                        const attractionId = parseInt(this.imageModalContext.split(':')[1]);
                        if (!isNaN(attractionId)) {
                            this.updateAttraction(attractionId, 'image_path', url);
                        }
                        this.showImageModal = false;
                        this.activeImageTarget = null;
                        this.imageModalContext = 'element'; // Reset
                        return;
                    }

                    if (this.activeSection !== null) {
                        // Update either an element OR the section background
                        if (this.activeImageTarget === 'background') {
                            // Section background
                            const field = 'bg_image'; // This is the standard field name we used
                            // Just in case it's named differently in some components, 
                            // but for Hero it's bg_image
                            this.sections[this.activeSection].data[field] = url;
                        }
                        else if (this.activeType === 'image') {
                            // Floating Image Element
                            if (this.activeField) {
                                this.sections[this.activeSection].data[this.activeField] = url;
                            } else if (this.elementObjId) {
                                const elementId = this.elementObjId;
                                const elIndex = this.sections[this.activeSection].data.elements.findIndex(e => e.id === elementId);
                                if (elIndex > -1) {
                                    this.sections[this.activeSection].data.elements[elIndex].content = url;
                                }
                            }

                            // Update DOM for elements
                            const iframeWin = document.getElementById('editor-iframe').contentWindow;
                            if (iframeWin && this.activeElementSelector) {
                                const target = iframeWin.document.querySelector(this.activeElementSelector);
                                if (target) {
                                    if (target.tagName === 'IMG') {
                                        target.src = url;
                                    } else {
                                        const img = target.querySelector('img');
                                        if (img) img.src = url;
                                    }
                                }
                            }
                        }
                    }
                    this.showImageModal = false;
                    this.activeImageTarget = null;
                },

                get activeValue() {
                    if (this.activeSection === null) return '';
                    if (!this.sections[this.activeSection]) return '';
                    if (!this.sections[this.activeSection].data) return '';

                    if (this.activeField) {
                        return this.sections[this.activeSection].data[this.activeField] || '';
                    } else if (this.elementObjId) {
                        const id = this.elementObjId;
                        const elData = this.sections[this.activeSection].data.elements?.find(e => e.id == id);
                        return elData ? elData.content : '';
                    }
                    return '';
                },

                set activeValue(val) {
                    if (this.activeSection !== null) {
                        if (this.activeField) {
                            this.sections[this.activeSection].data[this.activeField] = val;

                            if (this.activeType === 'image' && this.activeElementSelector) {
                                const iframeWin = document.getElementById('editor-iframe').contentWindow;
                                if (iframeWin && iframeWin.document) {
                                    const target = iframeWin.document.querySelector(this.activeElementSelector);
                                    if (target) {
                                        if (target.tagName === 'IMG') target.src = val;
                                        else target.style.backgroundImage = `url("${val}")`;
                                    }
                                }
                            }
                        } else if (this.elementObjId) {
                            const id = this.elementObjId;
                            const elIndex = this.sections[this.activeSection].data.elements?.findIndex(e => e.id == id);
                            if (elIndex > -1) {
                                this.sections[this.activeSection].data.elements[elIndex].content = val;
                            }
                        }
                    }
                },

                updateElement() {
                    if (this.activeElementSelector && this.activeType === 'text') {
                        // Dynamically update iframe text
                        const iframeWin = document.getElementById('editor-iframe').contentWindow;
                        if (iframeWin && iframeWin.document) {
                            const target = iframeWin.document.querySelector(this.activeElementSelector);
                            if (target) {
                                target.innerText = this.activeValue;
                            }
                        }

                        // Push to underlying data proxy
                        if (this.activeSection !== null) {
                            if (this.elementObjId) {
                                const elements = this.sections[this.activeSection].data.elements;
                                const elIndex = elements.findIndex(e => e.id === this.elementObjId);
                                if (elIndex > -1) {
                                    elements[elIndex].content = this.activeValue;
                                }
                            } else if (this.activeField) {
                                this.sections[this.activeSection].data[this.activeField] = this.activeValue;
                            }
                        }
                    }
                },

                syncFromDOM(el) {
                    // Obsolete function since everything synchronizes via postMessage
                },

                updateSetting(key, value) {
                    // Update a generic setting for the currently active/focused section
                    // We can either require the user to explicitly pass the section index or resolve it from activeSection
                    const index = this.activeSection !== null ? this.activeSection : window.Alpine.store('editor')?.activeSection;
                    if (index !== null && this.sections[index]) {
                        this.sections[index].data[key] = value;
                        // Optional: trigger save automatically
                        // this.savePage();
                    } else {
                        // If no active section is set, we might be clicking directly on the input.
                        // We need a more robust way to find the section index. Let's find it via DOM.
                        const activeEl = document.activeElement;
                        const sectionIndexDOM = this.getSectionIndex(activeEl);
                        if (sectionIndexDOM !== null && this.sections[sectionIndexDOM]) {
                            this.sections[sectionIndexDOM].data[key] = value;
                        }
                    }
                },

                handleInput(e) {
                    // Kept for backward compatibility but mostly obsolete now that inputs happen in Iframe
                },

                deselect() {
                    this.activeSection = null;
                    this.activeElement = null;
                    this.activeElementSelector = null;
                    document.querySelectorAll('.draggable-element').forEach(e => e.classList.remove('selected'));
                    const iframeWin = document.getElementById('editor-iframe');
                    if (iframeWin && iframeWin.contentWindow) {
                        iframeWin.contentWindow.document.querySelectorAll('.draggable-element').forEach(e => e.classList.remove('selected'));
                        iframeWin.contentWindow.document.querySelectorAll('.section-wrapper').forEach(e => e.classList.remove('active'));
                    }
                },

                setPreview(width) {
                    this.previewWidth = width;
                },

                savePage() {
                    // Data is always up to date because `this.sections` is reactive and synced via postMessage.
                    this.saving = true;

                    // 1. Prepare payload
                    // Strip the runtime/editor-only properties if necessary, though it works fine as is
                    const orderedSections = JSON.parse(JSON.stringify(this.sections));

                    // 2. Put back inside page content format expected by backend
                    this.page.content = { sections: orderedSections };

                    fetch(`/admin/editor/${this.page.slug}?locale={{ app()->getLocale() }}`, {
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
                                // Optional: Reload to ensure backend state matches
                                // window.location.reload(); 
                                alert('Página guardada correctamente!');
                            } else {
                                alert('Error al guardar la página.');
                            }
                        })
                        .catch(err => {
                            this.saving = false;
                            console.error(err);
                            alert('Error de red al guardar la página.');
                        });
                },

                // Gallery Management
                uploadGalleryImage(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('gallery_images[]', file);
                    formData.append('show_in_carousel', '1'); // Auto-add to carousel

                    fetch('/admin/gallery', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.images) {
                                // Add new images to local array
                                this.carouselImages.push(...data.images);
                                this.galleryImages.push(...data.images);
                                alert('Imagen añadida al carrusel');
                            } else {
                                alert('Error al subir imagen: ' + (data.message || 'Desconocido'));
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Error de red al subir imagen');
                        });
                },

                deleteGalleryImage(id) {
                    if (!confirm('¿Estás seguro de eliminar esta imagen del carrusel?')) return;

                    fetch(`/admin/gallery/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Remove from local array
                                this.carouselImages = this.carouselImages.filter(img => img.id !== id);
                            } else {
                                alert('Error al eliminar imagen');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Error de red al eliminar imagen');
                        });
                },

                addToCarousel(id) {
                    fetch(`/admin/gallery/${id}/toggle-carousel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Add to local carousel array
                                // We need to find the full object from galleryImages
                                const image = this.galleryImages.find(img => img.id === id);
                                if (image) {
                                    this.carouselImages.push(image);
                                    // Close modal
                                    this.showGalleryModal = false;
                                    alert('Imagen añadida al carrusel');
                                }
                            } else {
                                alert('Error al añadir imagen');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Error de red al añadir imagen');
                        });
                },

                // Cafe Features Management
                addCafeFeature(sectionIndex) {
                    if (!this.sections[sectionIndex].data.features) {
                        this.sections[sectionIndex].data.features = [];
                    }
                    this.sections[sectionIndex].data.features.push({
                        id: 'f' + Date.now(),
                        icon: 'local_cafe',
                        label_es: '',
                        label_en: ''
                    });
                },

                deleteCafeFeature(sectionIndex, featureId) {
                    const features = this.sections[sectionIndex].data.features;
                    const idx = features.findIndex(f => f.id === featureId);
                    if (idx > -1) {
                        this.sections[sectionIndex].data.features.splice(idx, 1);
                    }
                },

                updateCafeFeature(sectionIndex, featureId, field, value) {
                    const features = this.sections[sectionIndex].data.features;
                    const feature = features.find(f => f.id === featureId);
                    if (feature) {
                        feature[field] = value;
                    }
                },

                // Attractions Management
                addAttraction() {
                    const formData = new FormData();
                    formData.append('title_es', 'Nuevo Atractivo');
                    formData.append('title_en', 'New Attraction');
                    formData.append('description_es', 'Descripción del atractivo...');
                    formData.append('description_en', 'Attraction description...');

                    fetch('/admin/attractions', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.attraction) {
                                this.attractions.push(data.attraction);
                                // Scroll to bottom logic if needed
                            } else {
                                alert('Error al crear atractivo');
                            }
                        })
                        .catch(err => console.error(err));
                },

                updateAttraction(id, field, value) {
                    const index = this.attractions.findIndex(a => a.id === id);
                    if (index === -1) return;

                    // Optimistic update
                    this.attractions[index][field] = value;
                    if (field === 'image_path') {
                        this.attractions[index]['image_url'] = value;
                    }

                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append(field, value);

                    fetch(`/admin/attractions/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .catch(err => console.error(err));
                },

                deleteAttraction(id) {
                    if (!confirm('¿Estás seguro de eliminar este atractivo?')) return;

                    fetch(`/admin/attractions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.attractions = this.attractions.filter(a => a.id !== id);
                            } else {
                                alert('Error al eliminar');
                            }
                        })
                        .catch(err => console.error(err));
                },

                getSectionIndex(el) {
                    // Helper to dynamically find the index based on DOM sorting
                    const sectionEl = el.closest('[data-section-index]');
                    return sectionEl ? parseInt(sectionEl.getAttribute('data-section-index')) : null;
                },

                getSectionData(index) {
                    return this.sections[index] ? this.sections[index].data : {};
                },

                getFieldStyle(index, fieldName) {
                    if (index === null || !this.sections[index]) return {};
                    const data = this.sections[index].data;
                    const getVal = (prop) => {
                        let val = data[fieldName + '_' + prop];
                        if (!val && (fieldName.endsWith('_es') || fieldName.endsWith('_en'))) {
                            const otherField = fieldName.endsWith('_es') ? fieldName.replace('_es', '_en') : fieldName.replace('_en', '_es');
                            val = data[otherField + '_' + prop];
                        }
                        return val;
                    };

                    const addPx = (val, defaultValue = 'inherit') => {
                        if (!val || val === 'inherit') return defaultValue;
                        if (!isNaN(val) && val !== '') return val + 'px';
                        return val;
                    };

                    const cleanLetterSpacing = (val) => {
                        if (val === 'tight') return '-0.025em';
                        if (val === 'wide') return '0.025em';
                        if (val === 'normal') return '0px';
                        return addPx(val, 'inherit');
                    };

                    const cleanLineHeight = (val) => {
                        if (!val || val === 'inherit') return 'inherit';
                        // If it's a pure number (like 1.2 or 1.5), keep it unitless. 
                        // If it's > 10, it's probably px.
                        if (!isNaN(val) && val !== '') {
                            return parseFloat(val) > 5 ? val + 'px' : val;
                        }
                        return val;
                    };

                    const textAlign = getVal('textAlign') || 'center';
                    const isCentered = textAlign === 'center';
                    return {
                        display: 'block',
                        width: 'fit-content',
                        marginLeft: isCentered ? 'auto' : '0',
                        marginRight: isCentered ? 'auto' : '0',
                        color: getVal('color') || 'inherit',
                        fontSize: addPx(getVal('fontSize')),
                        fontFamily: getVal('fontFamily') || 'inherit',
                        fontWeight: getVal('fontWeight') || 'inherit',
                        textAlign: textAlign,
                        textAlignLast: textAlign,
                        letterSpacing: cleanLetterSpacing(getVal('letterSpacing')),
                        lineHeight: cleanLineHeight(getVal('lineHeight')),
                        marginTop: addPx(getVal('marginTop'), '0px'),
                        marginBottom: addPx(getVal('marginBottom'), '0px'),
                        transform: `translate(${getVal('translateX') || 0}px, ${getVal('translateY') || 0}px)`,
                        whiteSpace: 'pre-wrap',
                        hyphens: 'none',
                        wordBreak: 'break-word',
                        wordSpacing: 'normal',
                        overflowWrap: 'break-word',
                        textJustify: 'none'
                    };
                },

                // Sanitization Helper
                sanitizeSections(sections) {
                    return sections.map(section => {
                        // Clone to detach references
                        const clean = JSON.parse(JSON.stringify(section));

                        // Ensure we only keep expected keys if needed, 
                        // but for now, the JSON structure is safe as it doesn't contain DOM nodes.
                        // We primarily want to ensure `elements` structure is clean.
                        if (clean.data && clean.data.elements) {
                            clean.data.elements = clean.data.elements.map(el => {
                                // Strip any runtime properties if they exist
                                // Interact.js modifies the styles manually, but our updateElementData
                                // updates the data. We trust the data.
                                delete el.active;
                                return el;
                            });
                        } return clean;
                    });
                }
            });
        </script>
</body>

</html>