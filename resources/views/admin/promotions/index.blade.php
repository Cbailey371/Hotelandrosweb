@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Popups Promocionales</h1>
            <a href="{{ route('admin.promotions.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <span class="material-symbols-outlined align-middle text-sm mr-1">add</span> Nuevo Popup
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promoción
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enlace
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($promotions as $promo)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($promo->media_type === 'image' && $promo->media_path)
                                            <img class="h-10 w-10 rounded object-cover" src="{{ $promo->media_path }}" alt="">
                                        @elseif($promo->media_type === 'video' && $promo->media_path)
                                            <video class="h-10 w-10 rounded object-cover bg-black" src="{{ $promo->media_path }}"
                                                muted></video>
                                        @else
                                            <div class="h-10 w-10 rounded bg-red-100 flex items-center justify-center text-red-600">
                                                <span class="material-symbols-outlined">play_circle</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $promo->title }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ ucfirst($promo->media_type) }}
                                            {{ $promo->youtube_id ? '(' . $promo->youtube_id . ')' : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Inicio:
                                    {{ $promo->starts_at ? $promo->starts_at->format('d/m/Y') : 'Inmediato' }}
                                </div>
                                <div class="text-sm text-gray-500">Fin:
                                    {{ $promo->ends_at ? $promo->ends_at->format('d/m/Y') : 'Indefinido' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($promo->link_url)
                                    <a href="{{ $promo->link_url }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-900 text-sm truncate max-w-[150px] block"
                                        title="{{ $promo->link_url }}">
                                        Ver Enlace
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">Sin enlace</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.promotions.toggle', $promo) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $promo->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.promotions.edit', $promo) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta promoción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No hay popups promocionales configurados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($promotions->hasPages())
                <div class="px-6 py-3 border-t">
                    {{ $promotions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection