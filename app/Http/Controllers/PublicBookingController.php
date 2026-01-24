<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Setting;
use App\Mail\BookingRequestMail;
use App\Mail\BookingConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicBookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
            'message' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create($validated);

        // Envío de correos
        $adminEmail = Setting::where('key', 'hotel_email')->value('value') ?? config('mail.from.address');

        try {
            // Correo para el administrador
            Mail::to($adminEmail)->send(new BookingRequestMail($booking));

            // Correo para el cliente (Confirmación)
            Mail::to($booking->email)->send(new BookingConfirmationMail($booking));

        } catch (\Exception $e) {
            \Log::error('Error enviando correos de reserva: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Tu solicitud ha sido enviada con éxito. Nos pondremos en contacto contigo pronto para confirmar disponibilidad y costos.');
    }
}
