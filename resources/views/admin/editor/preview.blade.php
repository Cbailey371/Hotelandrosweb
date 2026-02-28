<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="{{ ($settings['website_theme'] ?? 'light') == 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Editor Preview</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;700;900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Montserrat:wght@100;300;400;700;900&family=Roboto:wght@300;400;700;900&family=Merriweather:wght@300;400;700;900&family=Oswald:wght@400;700&family=Lora:wght@400;700&family=Dancing+Script:wght@400;700&family=Nunito:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Silence the -webkit-text-size-adjust warning in Firefox compatibility layer */
        html { -webkit-text-size-adjust: 100% !important; text-size-adjust: 100% !important; }
    </style>

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: transparent;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
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

        /* Editor Specific Tools injected by Parent */
        .cursor-wait {
            cursor: wait;
        }

        .draggable-offset {
            touch-action: none;
            user-select: none;
            cursor: move;
        }

        [contenteditable="true"] {
            user-select: auto !important;
            cursor: text !important;
            outline: none;
        }

        .section-wrapper {
            position: relative;
            outline: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .section-wrapper:hover {
            outline: 2px dashed #60a5fa;
            /* blue-400 */
        }

        .section-wrapper.active {
            outline: 2px solid #2563eb;
            /* blue-600 */
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
        }

        .section-toolbar {
            position: absolute;
            top: 0;
            right: 0;
            transform: translateY(-100%);
            background-color: #2563eb;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem 0.5rem 0 0;
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-in-out;
            z-index: 50;
        }

        .section-wrapper:hover .section-toolbar,
        .section-wrapper.active .section-toolbar {
            opacity: 1;
            visibility: visible;
        }

        .draggable-element.active::before,
        .draggable-element.active::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: #2563eb;
            border: 1px solid white;
            border-radius: 50%;
            z-index: 51;
        }

        .draggable-element.active::before {
            top: -4px;
            left: -4px;
        }

        .draggable-element.active::after {
            bottom: -4px;
            right: -4px;
        }

        .draggable-element.active {
            outline: 2px solid #2563eb;
            z-index: 50;
        }
    </style>
</head>

<body class="bg-white min-h-screen" x-data="previewEditor()">

    <div id="canvas-content">
        @if(isset($page->content['sections']))
            @foreach($page->content['sections'] as $index => $section)
                <div class="section-wrapper" data-section-index="{{ $index }}">
                    <!-- Section Toolbar (Click handlers are bound in parent window) -->
                    <div class="section-toolbar">
                        <button class="section-handle cursor-grab active:cursor-grabbing hover:text-blue-200 mt-0.5"
                            title="Drag Section">
                            <span class="material-symbols-outlined text-sm">drag_indicator</span>
                        </button>
                        <span class="font-bold uppercase tracking-wider self-center">{{ $section['type'] }}</span>

                        <div class="w-px h-4 bg-blue-400 mx-1 self-center"></div>

                        <button class="action-btn hover:text-blue-200 flex items-center gap-1" data-action="add-text"
                            data-index="{{ $index }}" title="Add Text">
                            <span class="material-symbols-outlined text-[14px]">title</span>
                        </button>
                        <button class="action-btn hover:text-blue-200 flex items-center gap-1" data-action="add-image"
                            data-index="{{ $index }}" title="Add Image">
                            <span class="material-symbols-outlined text-[14px]">image</span>
                        </button>

                        <div class="w-px h-4 bg-blue-400 mx-1 self-center"></div>

                        <button class="action-btn hover:text-blue-200" data-action="move-up" data-index="{{ $index }}">
                            <span class="material-symbols-outlined text-[10px]">arrow_upward</span>
                        </button>
                        <button class="action-btn hover:text-blue-200" data-action="move-down" data-index="{{ $index }}">
                            <span class="material-symbols-outlined text-[10px]">arrow_downward</span>
                        </button>
                        <button class="action-btn hover:text-red-200" data-action="delete" data-index="{{ $index }}">
                            <span class="material-symbols-outlined text-[10px]">delete</span>
                        </button>
                    </div>

                    <!-- Component Render -->
                    <x-dynamic-component :component="'sections.' . $section['type']" :data="$section['data']" :rooms="$rooms ?? []" :carouselImages="$carouselImages ?? []" :attractions="$attractions ?? []"
                            :index="$index" mode="editor" />
                </div>
            @endforeach
        @endif
    </div>

    <!-- Communication Script with Parent -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('previewEditor', () => ({
                sections: @json($page->content['sections'] ?? []),
                carouselImages: @json($carouselImages ?? []),
                galleryImages: @json($galleryImages ?? []),
                attractions: @json($attractions ?? []),

                init() {
                    window.addEventListener('message', (e) => {
                        if (e.data && e.data.type === 'UPDATE_STATE') {
                            if (e.data.sections) this.sections = e.data.sections;
                            if (e.data.carouselImages !== undefined) this.carouselImages = e.data.carouselImages;
                            if (e.data.galleryImages !== undefined) this.galleryImages = e.data.galleryImages;
                            if (e.data.attractions !== undefined) this.attractions = e.data.attractions;
                        } else if (e.data && e.data.type === 'UPDATE_SECTIONS') {
                            // Backwards compatibility with standard updates
                            if (e.data.sections) this.sections = e.data.sections;
                        }
                    });
                },

                // Proxy Methods to Main Editor
                deleteGalleryImage(id) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'deleteGalleryImage', id: id }, '*');
                },
                openImageModal(context) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'openImageModal', context: context }, '*');
                },
                updateAttraction(id, field, value) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'updateAttraction', id: id, field: field, value: value }, '*');
                },
                deleteCafeFeature(sectionIndex, featureId) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'deleteCafeFeature', index: sectionIndex, featureId: featureId }, '*');
                },
                addCafeFeature(sectionIndex) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'addCafeFeature', index: sectionIndex }, '*');
                },
                updateCafeFeature(sectionIndex, featureId, field, value) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'updateCafeFeature', index: sectionIndex, featureId: featureId, field: field, value: value }, '*');
                },
                deleteAttraction(id) {
                    window.parent.postMessage({ type: 'DATA_ACTION', action: 'deleteAttraction', id: id }, '*');
                },
                selectElement(el) {
                    // Method kept just in case some component still calls it, but minimized.
                    if (!el) return;
                    this._sendSelect(el);
                },

                _sendSelect(el) {
                    const sectionIndexEl = el.closest('[data-section-index]');
                    const elementId = el.dataset.elementId || null;

                    window.parent.postMessage({
                        type: 'SELECT_ELEMENT',
                        dataset: {
                            label: el.dataset.label || 'Element',
                            type: el.dataset.type || 'text',
                            field: el.dataset.field || null,
                            elementId: elementId
                        },
                        sectionIndex: sectionIndexEl ? parseInt(sectionIndexEl.dataset.sectionIndex) : null,
                        selector: elementId ? `[data-element-id="${elementId}"]` : (el.dataset.field ? `[data-field="${el.dataset.field}"]` : null)
                    }, '*');
                },

                getSectionData(index) {
                    return (this.sections[index] && this.sections[index].data) ? this.sections[index].data : {};
                },

                getSectionIndex(el) {
                    const sectionEl = el.closest('[data-section-index]');
                    return sectionEl ? parseInt(sectionEl.dataset.sectionIndex) : null;
                },

                getOtherField(field) {
                    if (!field) return null;
                    if (field.endsWith('_es')) return field.replace('_es', '_en');
                    if (field.endsWith('_en')) return field.replace('_en', '_es');
                    return null;
                },

                getFieldStyle(index, field) {
                    if (index === null || !this.sections[index] || !field) return {};
                    const data = this.sections[index].data;
                    const otherField = this.getOtherField(field);

                    const getVal = (prop) => {
                        let val = data[field + '_' + prop];
                        if (!val && otherField) val = data[otherField + '_' + prop];
                        return val || '';
                    };

                    const style = {};
                    const color = getVal('color'); if (color) style.color = color;
                    const fontSize = getVal('fontSize'); if (fontSize) style.fontSize = fontSize;
                    const fontFamily = getVal('fontFamily'); if (fontFamily) style.fontFamily = fontFamily;
                    const fontWeight = getVal('fontWeight'); if (fontWeight) style.fontWeight = fontWeight;
                    const textAlign = getVal('textAlign'); if (textAlign) style.textAlign = textAlign;

                    let letterSpacing = getVal('letterSpacing');
                    if (letterSpacing) {
                        if (letterSpacing === 'tight') style.letterSpacing = '-0.025em';
                        else if (letterSpacing === 'normal') style.letterSpacing = '0px';
                        else style.letterSpacing = letterSpacing;
                    }

                    const lineHeight = getVal('lineHeight'); if (lineHeight) style.lineHeight = lineHeight;
                    const marginTop = getVal('marginTop'); if (marginTop) style.marginTop = marginTop;
                    const marginBottom = getVal('marginBottom'); if (marginBottom) style.marginBottom = marginBottom;

                    const tx = getVal('translateX') || 0;
                    const ty = getVal('translateY') || 0;
                    if (tx != 0 || ty != 0) {
                        style.transform = `translate(${tx}px, ${ty}px)`;
                    }

                    return style;
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {
            const parentStore = window.parent.document.querySelector('[x-data]') && window.parent.document.querySelector('[x-data]').__x && window.parent.document.querySelector('[x-data]').__x.$data;

            if (parentStore) {
                // Let the parent know iframe is ready
                if (typeof parentStore.iframeReady === 'function') {
                    parentStore.iframeReady(document);
                }
            }

            // Bind clicks to parent Alpine functions
            document.addEventListener('click', (e) => {
                // 1. Toolbar Actions (e.g. Move Up/Down/Delete Section)
                const actionBtn = e.target.closest('.action-btn');
                if (actionBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.parent.postMessage({
                        type: 'EDITOR_ACTION',
                        action: actionBtn.dataset.action,
                        index: parseInt(actionBtn.dataset.index)
                    }, '*');
                    return;
                }

                // 2. Identification
                const editable = e.target.closest('[contenteditable="true"]') || e.target.closest('[data-field]');
                const section = e.target.closest('.section-wrapper');
                const isActionElement = e.target.closest('button, [\\@click], [x-on\\:click], .cursor-pointer');

                // 3. Choice of logic
                if (editable) {
                    const sectionIndexEl = editable.closest('[data-section-index]');
                    const elementId = editable.dataset.elementId || null;

                    window.parent.postMessage({
                        type: 'SELECT_ELEMENT',
                        dataset: {
                            label: editable.dataset.label || 'Element',
                            type: editable.dataset.type || 'text',
                            field: editable.dataset.field || null,
                            elementId: elementId
                        },
                        sectionIndex: sectionIndexEl ? parseInt(sectionIndexEl.dataset.sectionIndex) : null,
                        selector: elementId ? `[data-element-id="${elementId}"]` : (editable.dataset.field ? `[data-field="${editable.dataset.field}"]` : null)
                    }, '*');

                    // If it's NOT a specific action element, stop propagation to avoid triggering section selection
                    if (!isActionElement) {
                        e.stopPropagation();
                    }
                } else if (section) {
                    window.parent.postMessage({
                        type: 'SELECT_SECTION',
                        index: parseInt(section.dataset.sectionIndex)
                    }, '*');

                    // If it's NOT a specific action element, stop propagation
                    if (!isActionElement) {
                        e.stopPropagation();
                    }
                }
            }, true); // Use capture phase

            document.addEventListener('input', (e) => {
                const el = e.target;
                if (el.isContentEditable) {
                    const sectionIndex = el.closest('[data-section-index]')?.dataset.sectionIndex;
                    const field = el.dataset.field;
                    const elementId = el.dataset.elementId;

                    if (sectionIndex !== undefined) {
                        let text = el.innerText.replace(/[\n\r\s]+$/, '');

                        window.parent.postMessage({
                            type: 'SYNC_INPUT',
                            sectionIndex: parseInt(sectionIndex),
                            field: field || null,
                            elementId: elementId || null,
                            content: text
                        }, '*');
                    }
                }
            });

            // Dispatch custom event so Alpine components (like Swiper) restart correctly!
            setTimeout(() => {
                document.dispatchEvent(new CustomEvent('editor-preview-ready', { bubbles: true }));
            }, 300);
        });

        // Function allowing parent to re-render or update classes
        window.activateSection = (index) => {
            document.querySelectorAll('.section-wrapper').forEach(el => el.classList.remove('active'));
            const target = document.querySelector(`.section-wrapper[data-section-index="${index}"]`);
            if (target) target.classList.add('active');
        };

        // Expose a helper so parent can manipulate styles immediately
        window.updateElementStyle = (selector, prop, val, isSection = false, sectionIndex = null) => {
            let target = null;
            if (isSection) target = document.querySelector(`.section-wrapper[data-section-index="${sectionIndex}"]`);
            else if (selector) target = document.querySelector(selector);

            if (target) target.style[prop] = val;
        };

        // Initialize libraries when the editable DOM changes or loads
        document.addEventListener('editor-preview-ready', () => {
            if (typeof Swiper !== 'undefined') {
                try {
                    document.querySelectorAll('.swiper.home-carousel').forEach(el => {
                        if (!el.isConnected || !el.querySelector('.swiper-wrapper')) return;
                        
                        requestAnimationFrame(() => {
                            if (el.swiper) el.swiper.destroy(true, true);
                            
                            new Swiper(el, {
                                slidesPerView: 1.2,
                                spaceBetween: 20,
                                centeredSlides: true,
                                loop: true,
                                observer: true,
                                observeParents: true,
                                autoplay: { delay: 3500, disableOnInteraction: false },
                                breakpoints: { 640: { slidesPerView: 2.2 }, 1024: { slidesPerView: 3.2 } }
                            });
                        });
                    });

                    document.querySelectorAll('.swiper.room-images').forEach(el => {
                        const wrapper = el.querySelector('.swiper-wrapper');
                        if (!el.isConnected || !wrapper || wrapper.children.length === 0) return;

                        requestAnimationFrame(() => {
                            if (el.swiper) el.swiper.destroy(true, true);

                            const paginationEl = el.querySelector('.swiper-pagination');
                            const nextEl = el.querySelector('.swiper-button-next');
                            const prevEl = el.querySelector('.swiper-button-prev');

                            new Swiper(el, {
                                slidesPerView: 1,
                                loop: true,
                                observer: true,
                                observeParents: true,
                                pagination: paginationEl ? { el: paginationEl, clickable: true } : false,
                                navigation: (nextEl && prevEl) ? { nextEl: nextEl, prevEl: prevEl } : false
                            });
                        });
                    });
                } catch (e) {
                    console.warn("Swiper init postponed or failed safely:", e);
                }
            }
        });

        // Listen to Alpine updates to trigger re-renders safely
        let previewReadyTimeout = null;
        window.addEventListener('message', (e) => {
            if (e.data && (e.data.type === 'UPDATE_SECTIONS' || e.data.type === 'UPDATE_STATE')) {
                clearTimeout(previewReadyTimeout);
                previewReadyTimeout = setTimeout(() => {
                    document.dispatchEvent(new CustomEvent('editor-preview-ready'));
                }, 500);
            }
        });
    </script>
</body>

</html>