import Sortable from 'sortablejs';

console.log('Visual Editor Module Loaded');

document.addEventListener('alpine:init', () => {
    console.log('Alpine Init Event Fired');
    Alpine.data('editor', (initialPage) => ({
        page: initialPage,
        sections: initialPage.content?.sections || [],
        saving: false,
        activeSection: null,
        activeElement: null,
        activeElementLabel: 'Element',
        activeType: 'text',
        activeField: null,
        previewWidth: '100%',

        init() {
            console.log('Editor Initialized', this.sections);

            // Initialize Sortable
            const canvas = document.getElementById('editor-canvas');
            if (canvas) {
                new Sortable(canvas, {
                    animation: 150,
                    handle: '.section-handle',
                    ghostClass: 'opacity-50',
                    onEnd: (evt) => {
                        console.log('Reordered', evt.oldIndex, evt.newIndex);
                        // We rely on DOM order for saving, so no strict need to splice the array
                        // unless we wanted reactive updates elsewhere.
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

        get activeValue() {
            if (this.activeSection === null || !this.activeField) return '';
            // Safety check
            if (!this.sections[this.activeSection]) return '';
            if (!this.sections[this.activeSection].data) return '';

            return this.sections[this.activeSection].data[this.activeField] || '';
        },

        set activeValue(val) {
            if (this.activeSection !== null && this.activeField) {
                this.sections[this.activeSection].data[this.activeField] = val;

                // Direct DOM update for non-text inputs (like images)
                if (this.activeType === 'image' && this.activeElement) {
                    // Check if bg image or img src
                    if (this.activeElement.tagName === 'IMG') {
                        this.activeElement.src = val;
                    } else {
                        this.activeElement.style.backgroundImage = `url("${val}")`;
                    }
                }
            }
        },

        updateElement() {
            // Update DOM from Sidebar Textarea
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

            // Update content object
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
                        // Optional: fancy toast
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

        // Utility to resolve values with constraints (opacity etc)
        getNumber(val) {
            return parseFloat(val) || 0;
        }
    }));
});
