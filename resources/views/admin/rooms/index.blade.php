@extends('layouts.admin')

@section('header', 'Gestionar Habitaciones')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">Habitaciones
            </h1>
            <p class="text-[#4c739a] dark:text-slate-400 text-base font-normal">Gestiona el inventario de habitaciones y
                precios.</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}"
            class="flex min-w-[140px] cursor-pointer items-center justify-center gap-2 rounded-lg h-11 px-6 bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary/90 transition-all">
            <span class="material-symbols-outlined text-xl">add</span>
            <span>Nueva Habitación</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] dark:text-slate-400 uppercase tracking-wider">
                            Detalles</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] dark:text-slate-400 uppercase tracking-wider">
                            Precio / Noche</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] dark:text-slate-400 uppercase tracking-wider">
                            Capacidad</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] dark:text-slate-400 uppercase tracking-wider">
                            Estado</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-[#4c739a] dark:text-slate-400 uppercase tracking-wider text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($rooms as $room)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-12 rounded-lg bg-cover bg-center border border-slate-100 dark:border-slate-700 shadow-sm"
                                        style='background-image: url("{{ $room->image }}");'></div>
                                    <div>
                                        <p class="text-[#0d141b] dark:text-white font-semibold text-sm">{{ $room->name_es }}</p>
                                        <p class="text-[#4c739a] dark:text-slate-500 text-xs">{{ $room->name_en }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">
                                ${{ number_format($room->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#0d141b] dark:text-slate-300">
                                {{ $room->capacity }} Personas
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $room->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400' }}">
                                    {{ $room->status == 'active' ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.rooms.edit', $room) }}"
                                        class="p-2 text-[#4c739a] hover:text-primary hover:bg-primary/10 rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-[#4c739a] hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection