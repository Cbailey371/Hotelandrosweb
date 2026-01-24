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
        <button
            class="flex min-w-[140px] cursor-pointer items-center justify-center gap-2 rounded-lg h-11 px-6 bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary/90 transition-all">
            <span class="material-symbols-outlined text-xl">upload</span>
            <span>Subir Fotos</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($images as $image)
            <div
                class="relative group aspect-square rounded-[2rem] overflow-hidden border-2 {{ $image->show_in_carousel ? 'border-primary ring-4 ring-primary/10' : 'border-slate-200 dark:border-slate-800' }} transition-all shadow-sm bg-white dark:bg-slate-900">
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

                <!-- Carousel Badge -->
                @if($image->show_in_carousel)
                    <div class="absolute top-4 left-4">
                        <div
                            class="bg-primary text-white text-[10px] font-black px-3 py-1.5 rounded-full flex items-center gap-1 shadow-xl uppercase tracking-widest animate-in zoom-in">
                            <span class="material-symbols-outlined text-xs">view_carousel</span>
                            <span>Destacada</span>
                        </div>
                    </div>
                @endif
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