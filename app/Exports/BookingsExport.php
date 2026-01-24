<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $bookings;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Email Cliente',
            'Check-in',
            'Check-out',
            'Habitación',
            'Estado',
            'Total',
            'Fecha Reserva',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->customer_name,
            $booking->email,
            \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y'),
            \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y'),
            $booking->room->name ?? 'N/A',
            ucfirst($booking->status),
            $booking->total_price,
            $booking->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
