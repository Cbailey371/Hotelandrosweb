@extends('layouts.admin')

@section('header', 'Gestor de Galería')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">Galería de
                Fotos</h1>
            <p class="text-[#4c739a] dark:text-slate-400 text-base font-normal">Gestiona las imágenes que se muestran en la
                web.</p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="syncGalleryManual(this)"
                class="flex min-w-[140px] cursor-pointer items-center justify-center gap-2 rounded-lg h-11 px-6 bg-slate-100 text-slate-600 text-sm font-bold hover:bg-slate-200 transition-all border border-slate-200">
                <span class="material-symbols-outlined text-xl">sync</span>
                <span>Sincronizar Manual</span>
            </button>
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                class="flex min-w-[140px] cursor-pointer items-center justify-center gap-2 rounded-lg h-11 px-6 bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-xl">upload</span>
                <span>Subir Fotos</span>
            </button>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined">error</span>
                <span class="font-bold">Hubo un problema:</span>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Modal de Subida -->
    <div id="uploadModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 backdrop-blur-sm p-4">
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-xl font-black uppercase tracking-tight">Subir Nuevas Fotos</h3>
                <button onclick="document.getElementById('uploadModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Seleccionar Archivos
                        (Máx 10MB por foto)</label>
                    <div class="relative group h-48 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl hover:border-primary/50 transition-all flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 overflow-hidden"
                        id="drop-zone">
                        <input type="file" name="gallery_images[]" id="gallery_images_input" multiple required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                        <div
                            class="flex flex-col items-center justify-center text-slate-400 group-hover:text-primary transition-colors z-10 pointer-events-none">
                            <span class="material-symbols-outlined text-5xl mb-3 animate-pulse">cloud_upload</span>
                            <p class="text-[10px] uppercase font-black tracking-[0.2em] mb-1">Haz clic o arrastra aquí</p>
                            <p class="text-[9px] font-bold text-slate-400/60 uppercase" id="file-status">Ningún archivo
                                seleccionado</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="show_in_carousel" id="modal_carousel" value="1"
                            class="w-5 h-5 rounded border-slate-300 text-primary">
                        <label for="modal_carousel"
                            class="text-sm font-bold text-slate-600 dark:text-slate-300 cursor-pointer">Carrusel Inicio</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="show_in_cafe" id="modal_cafe" value="1"
                            class="w-5 h-5 rounded border-slate-300 text-primary">
                        <label for="modal_cafe"
                            class="text-sm font-bold text-slate-600 dark:text-slate-300 cursor-pointer">Galería Café</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                        class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">Cancelar</button>
                    <button type="submit"
                        class="px-10 py-3 bg-primary text-white font-black rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-widest text-xs">
                        Comenzar Subida
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($images as $image)
            <div
                class="relative group aspect-square rounded-[2rem] overflow-hidden border-2 {{ ($image->show_in_carousel || $image->show_in_cafe) ? 'border-primary ring-4 ring-primary/10' : 'border-slate-200 dark:border-slate-800' }} transition-all shadow-sm bg-white dark:bg-slate-900">
                <img src="{{ $image->image_path }}"
                    class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-500">

                <!-- Overlay Actions -->
                <div
                    class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3">
                    <form action="{{ route('admin.gallery.toggle-carousel', $image->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 {{ $image->show_in_carousel ? 'bg-amber-500 hover:bg-amber-600' : 'bg-primary hover:bg-primary/90' }} text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg transition-all scale-90 group-hover:scale-100">
                            <span
                                class="material-symbols-outlined text-sm">{{ $image->show_in_carousel ? 'visibility_off' : 'view_carousel' }}</span>
                            {{ $image->show_in_carousel ? 'Quitar carrusel' : 'A carrusel' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.gallery.toggle-cafe', $image->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 {{ $image->show_in_cafe ? 'bg-orange-500 hover:bg-orange-600' : 'bg-slate-700 hover:bg-slate-800' }} text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg transition-all scale-90 group-hover:scale-100">
                            <span
                                class="material-symbols-outlined text-sm">{{ $image->show_in_cafe ? 'coffee_maker' : 'local_cafe' }}</span>
                            {{ $image->show_in_cafe ? 'Quitar del Café' : 'A Galería Café' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.gallery.destroy', $image->id) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar esta imagen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all scale-90 group-hover:scale-100">
                            <span class="material-symbols-outlined text-xl">delete</span>
                        </button>
                    </form>
                </div>

                <!-- Badges -->
                <div class="absolute top-4 left-4 flex flex-col gap-2">
                    @if($image->show_in_carousel)
                        <div
                            class="bg-primary text-white text-[10px] font-black px-3 py-1.5 rounded-full flex items-center gap-1 shadow-xl uppercase tracking-widest animate-in zoom-in">
                            <span class="material-symbols-outlined text-xs">view_carousel</span>
                            <span>Destacada</span>
                        </div>
                    @endif
                    @if($image->show_in_cafe)
                        <div
                            class="bg-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-full flex items-center gap-1 shadow-xl uppercase tracking-widest animate-in zoom-in">
                            <span class="material-symbols-outlined text-xs">local_cafe</span>
                            <span>Café</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-20 text-center bg-white dark:bg-slate-900 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">image</span>
                <p class="text-slate-400">No hay imágenes en la galería.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        function syncGalleryManual(button) {
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="material-symbols-outlined text-xl animate-spin">sync</span><span>Sincronizando...</span>';
            button.classList.add('opacity-70', 'cursor-not-allowed');

            fetch("{{ route('admin.gallery.sync') }}?ajax=1", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        if (data.added_count > 0) {
                            window.location.reload();
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al sincronizar la galería.');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalContent;
                    button.classList.remove('opacity-70', 'cursor-not-allowed');
                });
        }

        const fileInput = document.getElementById('gallery_images_input');
        const fileStatus = document.getElementById('file-status');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const count = this.files.length;
                fileStatus.innerText = count > 0 ? (count === 1 ? '1 archivo seleccionado' : `${count} archivos seleccionados`) : 'Ningún archivo seleccionado';
            });
        }
    </script>
@endpush