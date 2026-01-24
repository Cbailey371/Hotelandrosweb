@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-[#cfdbe7] dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined">door_open</span>
                    </div>
                </div>
                <p class="text-[#4c739a] text-sm font-medium">Habitaciones Totales</p>
                <p class="text-[#0d141b] dark:text-white text-3xl font-bold mt-1">{{ $stats['rooms_count'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-[#cfdbe7] dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/20 rounded-lg text-orange-600">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </div>
                </div>
                <p class="text-[#4c739a] text-sm font-medium">Reservas Totales</p>
                <p class="text-[#0d141b] dark:text-white text-3xl font-bold mt-1">{{ $stats['bookings_count'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-[#cfdbe7] dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg text-emerald-600">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                </div>
                <p class="text-[#4c739a] text-sm font-medium">Reservas Pendientes</p>
                <p class="text-[#0d141b] dark:text-white text-3xl font-bold mt-1">{{ $stats['pending_bookings'] }}</p>
            </div>
        </div>

        <!-- Quick Actions -->
        @if(auth()->user()->hasAnyRole(['super_admin', 'supervisor']))
            <div class="space-y-4">
                <h3 class="text-[#0d141b] dark:text-white text-lg font-bold">Acciones Rápidas</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gestionar Habitaciones -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'supervisor']))
                        <div
                            class="bg-white dark:bg-slate-900 border border-[#cfdbe7] dark:border-slate-800 rounded-2xl p-8 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined">door_open</span>
                                </div>
                                <h4 class="text-[#0d141b] dark:text-white text-xl font-black">Gestionar Habitaciones</h4>
                            </div>
                            <p class="text-[#4c739a] text-sm mb-8 leading-relaxed">
                                Administra tu catálogo de habitaciones, precios y descripciones.
                            </p>
                            <a href="{{ route('admin.rooms.index') }}"
                                class="inline-flex items-center justify-center px-8 py-3.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                                Ir a Habitaciones
                            </a>
                        </div>
                    @endif

                    <!-- Configuración del Sitio -->
                    @if(auth()->user()->hasRole('super_admin'))
                        <div
                            class="bg-white dark:bg-slate-900 border border-[#cfdbe7] dark:border-slate-800 rounded-2xl p-8 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="w-12 h-12 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined">settings</span>
                                </div>
                                <h4 class="text-[#0d141b] dark:text-white text-xl font-black">Configuración del Sitio</h4>
                            </div>
                            <p class="text-[#4c739a] text-sm mb-8 leading-relaxed">
                                Personaliza los textos, colores y opciones generales de la plataforma.
                            </p>
                            <a href="{{ route('admin.settings.index') }}"
                                class="inline-flex items-center justify-center px-8 py-3.5 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white border border-[#cfdbe7] dark:border-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all shadow-sm">
                                Ir a Configuración
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection