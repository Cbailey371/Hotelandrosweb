@extends('layouts.admin')

@section('header', 'Nueva Habitación')

@section('content')
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">

                <!-- Room Media (Premium Selector) -->
                <div class="space-y-4">
                    <label class="block text-sm font-bold text-[#0d141b] dark:text-white flex items-center justify-between">
                        <span>Imagen de la Habitación</span>
                        <span class="text-[10px] text-slate-500 font-normal uppercase tracking-widest">Recomendado:
                            1200x800px</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div
                            class="relative group aspect-video bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all">
                            <img id="room-preview" src="https://placehold.co/600x400?text=Subir+Imagen"
                                class="w-full h-full object-cover">

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="main_image_id" id="main_image_id" value="">

                            <!-- Overlays -->
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                <label
                                    class="w-12 h-12 bg-white rounded-full flex items-center justify-center cursor-pointer hover:scale-110 transition-transform shadow-lg"
                                    title="Subir nueva">
                                    <span class="material-symbols-outlined text-primary">cloud_upload</span>
                                    <input type="file" name="image_file" class="hidden" onchange="previewMainImage(this)">
                                </label>
                                <button type="button" onclick="openGallerySelector()"
                                    class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                                    title="Elegir de la galería">
                                    <span class="material-symbols-outlined">photo_library</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">O
                                    pegar URL externa</label>
                                <input type="url" name="image_url" placeholder="https://ejemplo.com/imagen.jpg"
                                    class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/50">
                            </div>
                            <div class="bg-primary/5 dark:bg-primary/10 p-4 rounded-xl border border-primary/20">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary text-sm mt-0.5">info</span>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Subir una imagen nueva o seleccionar una de la galería la convertirá automáticamente
                                        en la **imagen principal** de esta habitación.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Selector Modal (Premium Visual) -->
                <div id="gallery-selector-modal"
                    class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
                    <div
                        class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[3rem] overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
                        <!-- Modal Header -->
                        <div class="p-10 pb-6 flex justify-between items-center">
                            <h3 class="text-2xl font-black uppercase tracking-widest text-slate-800 dark:text-white">
                                Seleccionar de Galería</h3>
                            <button type="button" onclick="closeGallerySelector()"
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 transition-all">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <!-- Modal Content (Grid) -->
                        <div class="p-10 pt-0 overflow-y-auto w-full">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                                @foreach($galleryImages as $gallery)
                                    <div class="relative group aspect-square rounded-[2rem] overflow-hidden cursor-pointer border-4 border-transparent hover:border-primary/30 transition-all shadow-sm"
                                        onclick="selectMainImage('{{ $gallery->id }}', '{{ $gallery->image_path }}')">
                                        <img src="{{ $gallery->image_path }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500">

                                        <!-- Selection Indicator (Matches reference) -->
                                        <div
                                            class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <div
                                                class="w-14 h-14 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-xl transform scale-50 group-hover:scale-100 transition-transform duration-300">
                                                <span
                                                    class="material-symbols-outlined text-primary text-3xl font-bold">check</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Modal Footer (Matches reference) -->
                        <div
                            class="p-10 pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6 bg-slate-50/50 dark:bg-slate-900/50">
                            <p class="text-sm font-medium italic text-slate-400">
                                Tip: You can upload more images from the "Global Gallery" section.
                            </p>
                            <button type="button" onclick="closeGallerySelector()"
                                class="px-10 py-4 bg-[#1e293b] text-white font-black rounded-2xl hover:bg-[#0f172a] transition-all uppercase tracking-widest shadow-lg shadow-slate-900/20">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Galería de Imágenes Adicionales -->
                <div class="border-t border-slate-200 dark:border-slate-800 pt-6">
                    <div class="flex items-center justify-between mb-6">
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white uppercase tracking-wider">
                            <span class="material-symbols-outlined align-middle mr-1 text-primary">photo_library</span>
                            Galería de la Habitación
                        </label>
                        <span class="text-xs text-slate-500 font-medium">Fotos anexadas a esta habitación</span>
                    </div>
                    
                    <div id="room-gallery-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        <!-- Action Card: Add from Global Gallery -->
                        <button type="button" onclick="openGallerySelector('additional')" 
                            class="relative group aspect-square rounded-[1.5rem] border-2 border-dashed border-primary/30 hover:border-primary bg-primary/5 hover:bg-primary/10 flex flex-col items-center justify-center transition-all shadow-sm">
                            <div class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center mb-2 shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-primary">add_photo_alternate</span>
                            </div>
                            <span class="text-[10px] font-black text-primary uppercase tracking-widest">Desde Galería</span>
                        </button>

                        <!-- Action Card: Upload New -->
                        <label class="relative group cursor-pointer aspect-square rounded-[1.5rem] border-2 border-dashed border-green-600/30 hover:border-green-600 bg-green-50/50 dark:bg-green-900/10 flex flex-col items-center justify-center transition-all shadow-sm">
                            <input type="file" name="new_gallery_images[]" multiple accept="image/*" class="hidden" onchange="previewNewImages(this)">
                            <div class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center mb-2 shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-green-600">cloud_upload</span>
                            </div>
                            <span class="text-[10px] font-black text-green-600 uppercase tracking-widest">Subir Nuevas</span>
                        </label>
                    </div>

                    <!-- Preview Container for New Uploads -->
                    <div id="new-uploads-preview" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-6 hidden">
                        <div class="col-span-full border-t border-slate-100 dark:border-slate-800 pt-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Fotos por subir (Temporal)</p>
                        </div>
                    </div>
                </div>

                <!-- Nombres -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Nombre (Español)</label>
                        <input type="text" name="name_es" required
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Nombre (Inglés)</label>
                        <input type="text" name="name_en" required
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50">
                    </div>
                </div>

                <!-- Descripciones -->
                <div>
                    <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Descripción (Español)</label>
                    <textarea name="description_es" rows="3"
                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Descripción (Inglés)</label>
                    <textarea name="description_en" rows="3"
                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50"></textarea>
                </div>

                <!-- Specs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Precio por Noche</label>
                        <input type="number" name="price" step="0.01" required
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Capacidad
                            (Personas)</label>
                        <input type="number" name="capacity" required
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Estado</label>
                        <select name="status"
                            class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0d141b] dark:text-white mb-2">Amenidades (Separadas por
                        coma)</label>
                    <input type="text" name="amenities"
                        class="w-full bg-[#f0f2f5] dark:bg-slate-800 border-none rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50"
                        placeholder="WiFi, TV, Minibar">
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.rooms.index') }}"
                    class="px-6 py-2.5 rounded-lg border border-[#cfdbe7] text-[#4c739a] font-bold hover:bg-white transition-all">Cancelar</a>
                <button type="submit"
                    class="px-8 py-2.5 rounded-lg bg-primary text-white font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Guardar
                    Habitación</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewMainImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('room-preview').src = e.target.result;
                    document.getElementById('main_image_id').value = '';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        let gallerySelectorMode = 'main'; // 'main' o 'additional'

        function openGallerySelector(mode = 'main') {
            gallerySelectorMode = mode;
            const modal = document.getElementById('gallery-selector-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            const title = modal.querySelector('h3');
            title.innerText = mode === 'main' ? 'Seleccionar Imagen Principal' : 'Añadir a Galería de Habitación';
        }

        function closeGallerySelector() {
            const modal = document.getElementById('gallery-selector-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function selectMainImage(id, path) {
            if (gallerySelectorMode === 'main') {
                document.getElementById('room-preview').src = path;
                document.getElementById('main_image_id').value = id;
            } else {
                addAdditionalImage(id, path);
            }
            closeGallerySelector();
        }

        function addAdditionalImage(id, path) {
            if (document.getElementById(`gallery-item-${id}`)) return;

            const container = document.getElementById('room-gallery-container');
            const div = document.createElement('div');
            div.className = 'relative group aspect-square rounded-[1.5rem] overflow-hidden border-2 border-slate-100 dark:border-slate-800 shadow-sm animate-in zoom-in duration-300';
            div.id = `gallery-item-${id}`;
            div.innerHTML = `
                    <input type="checkbox" name="gallery_ids[]" value="${id}" checked class="hidden">
                    <img src="${path}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeGalleryItem('${id}')" 
                        class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                `;
            container.appendChild(div);
        }

        function removeGalleryItem(id) {
            const item = document.getElementById(`gallery-item-${id}`);
            if (item) {
                item.classList.add('zoom-out', 'fade-out');
                setTimeout(() => item.remove(), 200);
            }
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewNewImages(input) {
            const container = document.getElementById('new-uploads-preview');
            const title = container.firstElementChild;
            container.innerHTML = '';
            container.appendChild(title);

            if (input.files && input.files.length > 0) {
                container.classList.remove('hidden');
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 relative';
                        div.innerHTML = `
                                        <img src="${e.target.result}" class="w-full h-full object-cover">
                                        <div class="absolute top-1 right-1 bg-green-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">Nueva</div>
                                    `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
@endpush