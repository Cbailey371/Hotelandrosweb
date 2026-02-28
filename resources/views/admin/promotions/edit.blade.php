@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Popup Promocional</h1>
            <a href="{{ route('admin.promotions.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Volver</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Error:</strong>
                <ul class="list-disc pl-5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-lg shadow px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title"> Título Interno </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="title" name="title" type="text" value="{{ old('title', $promotion->title) }}" required>
                    <p class="text-xs text-gray-500 mt-1">Solo para identificarlo en el administrador.</p>
                </div>

                <div
                    class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border rounded bg-slate-50 border-slate-200">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="media_path">
                            Opción 1: Reemplazar Flyer (Imagen/Video nativo)
                        </label>
                        @if(in_array($promotion->media_type, ['image', 'video']) && $promotion->media_path)
                            <div class="mb-2">
                                @if($promotion->media_type === 'video')
                                    <video src="{{ $promotion->media_path }}" class="h-16 rounded object-cover border"
                                        muted></video>
                                @else
                                    <img src="{{ $promotion->media_path }}" alt="Flyer actual"
                                        class="h-16 rounded object-cover border">
                                @endif
                                <p class="text-xs text-gray-500 mt-1">Actual: {{ basename($promotion->media_path) }}</p>
                            </div>
                        @endif
                        <input
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            id="media_path" name="media_path" type="file" accept="image/*,video/webm,video/mp4">
                        <p class="text-xs text-gray-500 mt-1">Sube un nuevo archivo para sobreescribir la promoción actual.
                        </p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="youtube_id">
                            Opción 2: ID Video de YouTube
                        </label>
                        @if($promotion->media_type === 'youtube' && $promotion->youtube_id)
                            <div class="mb-2">
                                <p class="text-sm font-medium text-blue-600">Video Activo: {{ $promotion->youtube_id }}</p>
                            </div>
                        @endif
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="youtube_id" name="youtube_id" type="text" placeholder="Ej: dQw4w9WgXcQ"
                            value="{{ old('youtube_id', $promotion->youtube_id) }}">
                        <p class="text-xs text-gray-500 mt-1">El video reemplazará cualquier imagen guardada.</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="link_url"> Enlace de Destino (Opcional)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="link_url" name="link_url" type="url" placeholder="https://..."
                        value="{{ old('link_url', $promotion->link_url) }}">
                    <p class="text-xs text-gray-500 mt-1">¿A dónde debe ir el usuario si hace clic en el popup?</p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="starts_at"> Mostrar Desde (Opcional)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="starts_at" name="starts_at" type="datetime-local"
                        value="{{ old('starts_at', $promotion->starts_at ? $promotion->starts_at->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="ends_at"> Mostrar Hasta (Opcional)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="ends_at" name="ends_at" type="datetime-local"
                        value="{{ old('ends_at', $promotion->ends_at ? $promotion->ends_at->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="md:col-span-2 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600 rounded" {{ $promotion->is_active ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 font-bold">Promoción Activa</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Actualizar Popup
                </button>
            </div>
        </form>
    </div>
@endsection