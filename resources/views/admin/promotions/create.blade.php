@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Crear Popup Promocional</h1>
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

        <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-lg shadow px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title"> Título Interno </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="title" name="title" type="text" value="{{ old('title') }}" required>
                    <p class="text-xs text-gray-500 mt-1">Solo para identificarlo en el administrador.</p>
                </div>

                <div
                    class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border rounded bg-slate-50 border-slate-200">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="media_path"> Opción 1: Subir Flyer
                            (Imagen/Video) </label>
                        <input
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            id="media_path" name="media_path" type="file" accept="image/*,video/webm,video/mp4">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="youtube_id"> Opción 2: ID Video de
                            YouTube </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="youtube_id" name="youtube_id" type="text" placeholder="Ej: dQw4w9WgXcQ"
                            value="{{ old('youtube_id') }}">
                        <p class="text-xs text-gray-500 mt-1">Si insertas un ID, el Flyer será ignorado.</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="link_url"> Enlace de Destino (Opcional)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="link_url" name="link_url" type="url" placeholder="https://..." value="{{ old('link_url') }}">
                    <p class="text-xs text-gray-500 mt-1">¿A dónde debe ir el usuario si hace clic en el popup?</p>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="starts_at"> Mostrar Desde (Opcional)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at') }}">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="ends_at"> Mostrar Hasta (Opcional)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at') }}">
                </div>

                <div class="md:col-span-2 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                        <span class="ml-2 text-gray-700 font-bold">Activar Promoción Inmediatamente</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Guardar Popup
                </button>
            </div>
        </form>
    </div>
@endsection