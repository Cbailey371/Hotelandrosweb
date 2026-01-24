@extends('layouts.admin')

@section('header', 'Reportes de Reservas')

@section('content')
    <div class="flex flex-col gap-8">
        <!-- Filters Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="flex flex-col gap-6">
                <!-- KPIs Summary (only if results exist) -->
                @if(isset($bookings))
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <div class="p-4 bg-primary/10 rounded-2xl border border-primary/20">
                            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Total Ingresos</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">
                                ${{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div
                            class="p-4 bg-purple-100 dark:bg-purple-900/20 rounded-2xl border border-purple-200 dark:border-purple-800">
                            <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-1">
                                Total Reservas</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalBookings }}</h3>
                        </div>
                        <div
                            class="p-4 bg-green-100 dark:bg-green-900/20 rounded-2xl border border-green-200 dark:border-green-800">
                            <p class="text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-widest mb-1">
                                Confirmadas</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $confirmedBookings }}</h3>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Fecha
                            Inicio</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Fecha
                            Fin</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Estado</label>
                        <select name="status"
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                            <option value="all">Todos</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmada
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" name="action" value="filter"
                            class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">filter_list</span>
                            Filtrar
                        </button>
                        @if(isset($bookings) && $bookings->count() > 0)
                            <button type="submit" name="action" value="pdf"
                                class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-700 transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                                Exportar PDF
                            </button>
                            <button type="submit" name="action" value="excel"
                                class="bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-green-600 transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">table_view</span>
                                Exportar Excel
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Table -->
        @if(isset($bookings))
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 text-xs uppercase tracking-wider text-slate-500 font-bold">
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Entrada / Salida</th>
                                <th class="px-6 py-4">Habitación</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono text-slate-500">#{{ $booking->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-slate-800 dark:text-white text-sm">{{ $booking->customer_name }}</span>
                                            <span class="text-xs text-slate-500">{{ $booking->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        <div class="flex flex-col">
                                            <span>In: {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</span>
                                            <span>Out: {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $booking->room->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800 dark:text-white">
                                        ${{ number_format($booking->total_price, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
                                        No se encontraron reservas con los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection