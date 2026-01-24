@extends('layouts.admin')

@section('header', 'Editar Reserva')

@section('content')
    <div class="max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('admin.bookings.index') }}"
                class="flex items-center gap-2 text-sm font-bold text-primary mb-4 hover:gap-3 transition-all">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Volver a la Bandeja
            </a>
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">
                Editar Reserva #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
            </h1>
        </div>

        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Sección: Información del Cliente -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Datos del Cliente
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Nombre
                            Completo</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $booking->customer_name) }}"
                            required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Correo
                            Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $booking->email) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $booking->phone) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">País</label>
                        <input type="text" name="country" value="{{ old('country', $booking->country) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                </div>
            </div>

            <!-- Sección: Detalles de la Estancia -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined">hotel</span>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Detalles de
                        Estancia</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Habitación</label>
                        <select name="room_id" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                                    {{ $room->name_es }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Huéspedes</label>
                        <input type="number" name="guests" value="{{ old('guests', $booking->guests) }}" required min="1"
                            max="10"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Check-In</label>
                        <input type="date" name="check_in" value="{{ old('check_in', $booking->check_in) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Check-Out</label>
                        <input type="date" name="check_out" value="{{ old('check_out', $booking->check_out) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                </div>
            </div>

            <!-- Sección: Estado y Comentarios -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined">settings_accessibility</span>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Estado y Notas
                    </h2>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Estado de la
                            Reserva</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label
                                class="relative flex cursor-pointer rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm focus:outline-none transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary/10">
                                <input type="radio" name="status" value="pending" class="sr-only" {{ $booking->status == 'pending' ? 'checked' : '' }}>
                                <div class="flex w-full items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">Pendiente</div>
                                    </div>
                                    <span
                                        class="material-symbols-outlined text-primary opacity-0 radio-check transition-opacity">check_circle</span>
                                </div>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm focus:outline-none transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-green-600 has-[:checked]:ring-2 has-[:checked]:ring-green-600/10">
                                <input type="radio" name="status" value="confirmed" class="sr-only" {{ $booking->status == 'confirmed' ? 'checked' : '' }}>
                                <div class="flex w-full items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">Confirmar</div>
                                    </div>
                                    <span
                                        class="material-symbols-outlined text-green-600 opacity-0 radio-check transition-opacity">check_circle</span>
                                </div>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm focus:outline-none transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50 has-[:checked]:border-red-600 has-[:checked]:ring-2 has-[:checked]:ring-red-600/10">
                                <input type="radio" name="status" value="cancelled" class="sr-only" {{ $booking->status == 'cancelled' ? 'checked' : '' }}>
                                <div class="flex w-full items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">Cancelar</div>
                                    </div>
                                    <span
                                        class="material-symbols-outlined text-red-600 opacity-0 radio-check transition-opacity">check_circle</span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-4 text-[11px] text-[#4c739a] italic font-medium">
                            * Al cambiar a "Confirmar", se enviará automáticamente un correo electrónico al cliente.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Mensaje/Comentarios</label>
                        <textarea name="message" rows="4"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-primary/50 resize-none">{{ old('message', $booking->message) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 p-4">
                <a href="{{ route('admin.bookings.index') }}"
                    class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all">
                    Descartar Cambios
                </a>
                <button type="submit"
                    class="px-12 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-widest text-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <style>
        input:checked+div .radio-check {
            opacity: 1;
        }
    </style>
@endsection