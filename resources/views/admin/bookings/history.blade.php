@extends('layouts.admin')

@section('header', 'Historial de Reservas')

@section('content')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">Historial de Reservas</h1>
            <p class="text-[#4c739a] dark:text-slate-400 text-base font-normal">Consulta las estadías completadas anteriormente (Check-out pasado) y reservaciones canceladas.</p>
        </div>
    </div>

    <!-- Barra de Filtros -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 mb-8 shadow-sm">
        <form action="{{ route('admin.bookings.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#4c739a] pl-1">Búsqueda</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nombre o email..." 
                           class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary/50 transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#4c739a] pl-1">Estado</label>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary/50 cursor-pointer">
                    <option value="">Confirmadas y Canceladas</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Solo Confirmadas</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Solo Canceladas</option>
                </select>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-[#4c739a] pl-1">Habitación</label>
                <select name="room_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary/50 cursor-pointer">
                    <option value="">Todas las habitaciones</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name_es }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white font-black py-3 rounded-xl hover:bg-slate-900 transition-all text-xs uppercase tracking-widest shadow-lg flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    Filtrar
                </button>
                <a href="{{ route('admin.bookings.history') }}" class="px-4 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center" title="Limpiar Filtros">
                    <span class="material-symbols-outlined">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    <div
        class="bg-white dark:bg-slate-900 border border-[#cfdbe7] dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50">
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] uppercase tracking-wider">Huésped</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] uppercase tracking-wider">Habitación</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] uppercase tracking-wider">Fechas</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#4c739a] uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-800">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold text-[#0d141b] dark:text-white">
                                        {{ $booking->customer_name }}</p>
                                    <p class="text-xs text-[#4c739a]">{{ $booking->email }}</p>
                                    @if($booking->country)
                                        <p class="text-[10px] text-[#4c739a] font-bold uppercase tracking-widest mt-1 italic opacity-80">
                                            🌍 {{ $booking->country }}
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4c739a] font-medium">
                                {{ $booking->room->name_es }} 
                                <span class="text-xs text-slate-400 font-normal">({{ $booking->number_of_rooms ?? 1 }} {{ Str::plural('Hab', $booking->number_of_rooms ?? 1) }})</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4c739a]">{{ $booking->check_in }} - {{ $booking->check_out }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[13px] font-bold">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'confirmed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pendiente',
                                        'confirmed' => 'Confirmada',
                                        'cancelled' => 'Cancelada',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 {{ $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                       class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all"
                                       title="Editar Reserva">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta reserva? Esta acción no se puede deshacer.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                title="Eliminar Reserva">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">No hay reservas en el historial.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
