<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        if (class_exists(\Barryvdh\Debugbar\Facades\Debugbar::class)) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        $query = \App\Models\Booking::query();

        // Filtro por Fechas
        if ($request->filled('start_date')) {
            $query->whereDate('check_in', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_out', '<=', $request->end_date);
        }

        // Filtro por Estado
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtro por Tipo de Habitación
        if ($request->filled('room_id') && $request->room_id !== 'all') {
            $query->where('room_id', $request->room_id);
        }

        $bookings = $query->with(['room'])->orderBy('check_in', 'desc')->get();

        // Calcular KPIs Básicos para el PDF
        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();

        $data = [
            'bookings' => $bookings,
            'filters' => $request->all(),
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'generated_at' => now(),
            'generated_by' => auth()->user()->name
        ];

        if ($request->action === 'pdf' && isset($bookings)) {
            if (ob_get_length())
                ob_end_clean();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', $data);
            return $pdf->download('reporte_reservas_' . now()->format('Ymd_His') . '.pdf');
        }

        if ($request->action === 'excel' && isset($bookings)) {
            if (ob_get_length())
                ob_end_clean();
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BookingsExport($bookings), 'reporte_reservas_' . now()->format('Ymd_His') . '.xlsx');
        }

        return view('admin.reports.index', $data);
    }
}
