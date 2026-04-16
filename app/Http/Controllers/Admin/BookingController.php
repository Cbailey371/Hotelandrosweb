<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('room')->active();

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por habitación
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $bookings = $query->latest()->get();
        $rooms = \App\Models\Room::all();

        return view('admin.bookings.index', compact('bookings', 'rooms'));
    }

    public function history(Request $request)
    {
        $query = Booking::with('room')->history();

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por estado específico en historial
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por habitación
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $bookings = $query->latest()->get();
        $rooms = \App\Models\Room::all();

        return view('admin.bookings.history', compact('bookings', 'rooms'));
    }

    public function show(Booking $booking)
    {
        $booking->load('room');
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $rooms = \App\Models\Room::all();
        return view('admin.bookings.edit', compact('booking', 'rooms'));
    }

    public function update(Request $request, Booking $booking)
    {
        $oldStatus = $booking->status;

        $rules = [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'number_of_rooms' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled',
            'message' => 'nullable|string',
        ];

        // Solo super_admin puede editar montos financieros directamente
        if (auth()->user()->role === 'super_admin') {
            $rules['base_price'] = 'required|numeric|min:0';
            $rules['extra_person_total'] = 'required|numeric|min:0';
            $rules['tax_amount'] = 'required|numeric|min:0';
            $rules['total_amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        $booking->update($validated);

        // Si se solicitó enviar correo de actualización
        if ($request->boolean('send_update_email')) {
            try {
                \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingUpdatedMail($booking));
                \Log::info('Correo de actualización enviado para reserva ID: ' . $booking->id);
            } catch (\Exception $e) {
                \Log::error('Error enviando correo de actualización (ID: ' . $booking->id . '): ' . $e->getMessage());
            }
        }
        // Si el estado cambió a confirmado y no se envió actualización explícita, enviamos el procesado estándar
        elseif ($oldStatus !== Booking::STATUS_CONFIRMED && $booking->status === Booking::STATUS_CONFIRMED) {
            try {
                \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingProcessedMail($booking));
            } catch (\Exception $e) {
                \Log::error('Error enviando correo de confirmación estándar (ID: ' . $booking->id . '): ' . $e->getMessage());
            }
        }

        if ($booking->status === Booking::STATUS_PENDING || ($booking->status === Booking::STATUS_CONFIRMED && $booking->check_out >= now()->toDateString())) {
            return redirect()->route('admin.bookings.index')->with('success', 'Reserva actualizada correctamente.');
        }

        return redirect()->route('admin.bookings.history')->with('success', 'Reserva procesada y enviada al historial.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Reserva eliminada correctamente.');
    }
}
