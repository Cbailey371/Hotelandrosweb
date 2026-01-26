@extends('layouts.admin')

@section('header', 'Detalle de Reserva')

@section('content')
    <div class="max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-2 text-sm font-bold text-primary mb-4 hover:gap-3 transition-all">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Volver a la Bandeja
            </a>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-[#0d141b] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">
                        Reserva #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                    </h1>
                    <p class="text-secondary mt-1 font-medium">Recibida el {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.bookings.edit', $booking) }}" 
                       class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Editar
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Info Principal -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
                    <div class="p-8">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">person</span>
                            Información del Huésped
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nombre</p>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $booking->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email</p>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $booking->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Teléfono</p>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $booking->phone ?? 'No provisto' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">País</p>
                                <p class="font-bold text-primary">{{ $booking->country ?? 'No provisto' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
                    <div class="p-8">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600">hotel</span>
                            Detalles de la Reserva
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Habitación</p>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $booking->room->name_es }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Huéspedes</p>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $booking->guests }} Persona(s)</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Estancia</p>
                                <p class="font-bold text-slate-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                @php
                                    $nights = \Carbon\Carbon::parse($booking->check_in)->diffInDays($booking->check_out) ?: 1;
                                @endphp
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Duración</p>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $nights }} Noche(s)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Costos -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
                    <div class="p-8">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6 flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">payments</span>
                            Resumen de Costos
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Tarifa Base (${{ number_format($booking->base_price, 2) }} x {{ $nights }} noches)</p>
                                <p class="font-bold text-slate-800 dark:text-white">${{ number_format($booking->base_price * $nights, 2) }}</p>
                            </div>
                            @if($booking->extra_person_total > 0)
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Cargos Extra Personas</p>
                                <p class="font-bold text-slate-800 dark:text-white">${{ number_format($booking->extra_person_total, 2) }}</p>
                            </div>
                            @endif
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Impuestos (ITBMS)</p>
                                <p class="font-bold text-slate-800 dark:text-white">${{ number_format($booking->tax_amount, 2) }}</p>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <p class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Total de la Reserva</p>
                                <p class="text-2xl font-black text-primary">${{ number_format($booking->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($booking->message)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm p-8">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-amber-500">sticky_note_2</span>
                            Notas / Requerimientos
                        </h3>
                        <p class="text-[#4c739a] leading-relaxed italic">"{{ $booking->message }}"</p>
                    </div>
                @endif
            </div>

            <!-- Columna Derecha: Estado -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm p-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Estado Actual</p>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-100 text-amber-700 font-black',
                            'confirmed' => 'bg-green-100 text-green-700 font-black',
                            'cancelled' => 'bg-red-100 text-red-700 font-black',
                        ];
                        $statusLabels = [
                            'pending' => 'PENDIENTE',
                            'confirmed' => 'CONFIRMADA',
                            'cancelled' => 'CANCELADA',
                        ];
                    @endphp
                    <div class="flex items-center gap-3 p-4 rounded-2xl {{ $statusClasses[$booking->status] }} text-center justify-center text-sm tracking-widest">
                        <span class="material-symbols-outlined">
                            {{ $booking->status == 'confirmed' ? 'verified' : ($booking->status == 'cancelled' ? 'cancel' : 'schedule') }}
                        </span>
                        {{ $statusLabels[$booking->status] }}
                    </div>
                </div>

                <div class="bg-slate-800 text-white rounded-[2rem] p-8 shadow-xl">
                    <h4 class="font-black text-lg mb-4 uppercase tracking-tight">Acciones Rápidas</h4>
                    <p class="text-slate-400 text-xs mb-6">Administra el flujo de la reserva desde aquí.</p>
                    <div class="space-y-3">
                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <input type="hidden" name="customer_name" value="{{ $booking->customer_name }}">
                            <input type="hidden" name="email" value="{{ $booking->email }}">
                            <input type="hidden" name="room_id" value="{{ $booking->room_id }}">
                            <input type="hidden" name="check_in" value="{{ $booking->check_in }}">
                            <input type="hidden" name="check_out" value="{{ $booking->check_out }}">
                            <input type="hidden" name="guests" value="{{ $booking->guests }}">
                            <input type="hidden" name="base_price" value="{{ $booking->base_price }}">
                            <input type="hidden" name="extra_person_total" value="{{ $booking->extra_person_total }}">
                            <input type="hidden" name="tax_amount" value="{{ $booking->tax_amount }}">
                            <input type="hidden" name="total_amount" value="{{ $booking->total_amount }}">
                            <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg shadow-green-900/40">MARCAR COMO CONFIRMADA</button>
                        </form>
                        
                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="block w-full py-4 bg-white/10 hover:bg-white/20 rounded-xl font-black uppercase text-[10px] tracking-widest text-center transition-all">EDITAR DETALLES</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
