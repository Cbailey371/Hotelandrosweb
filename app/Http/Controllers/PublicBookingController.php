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
        \Log::info('Intento de reserva recibido:', $request->all());

        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'country' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:50', // Aumentado de 20 a 50
                'room_id' => 'required|exists:rooms,id',
                'check_in' => 'required|date', // Eliminado after_or_equal:today temporalmente para evitar problemas de zona horaria
                'check_out' => 'required|date|after:check_in',
                'guests' => 'required|integer|min:1|max:10',
                'message' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Fallo de validación en reserva:', $e->errors());
            throw $e;
        }

        try {
            $room = Room::findOrFail($validated['room_id']);

            // Cálculo de noches
            $checkIn = \Carbon\Carbon::parse($validated['check_in']);
            $checkOut = \Carbon\Carbon::parse($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            if ($nights < 1)
                $nights = 1; // Mínimo 1 noche

            // Cálculos de precios
            $basePriceTotal = $room->price * $nights;

            $extraGuests = max(0, $validated['guests'] - $room->capacity);
            $extraPersonTotal = $extraGuests * $room->extra_person_charge * $nights;

            $taxAmount = ($basePriceTotal + $extraPersonTotal) * ($room->tax_percentage / 100);
            $totalAmount = $basePriceTotal + $extraPersonTotal + $taxAmount;

            // Combinar con los datos validados
            $bookingData = array_merge($validated, [
                'nights' => $nights,
                'base_price' => $room->price, // Guardamos el precio unitario por noche
                'extra_person_total' => $extraPersonTotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);

            $booking = Booking::create($bookingData);
            \Log::info('Reserva creada con éxito ID: ' . $booking->id . ' Total: ' . $totalAmount);
        } catch (\Exception $e) {
            \Log::error('ERROR FATAL al crear reserva en BD: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al guardar tu reserva. Inténtalo de nuevo.'])->withInput();
        }

        // Envío de correos
        \Illuminate\Support\Facades\Mail::purge('smtp');

        $adminEmail = Setting::where('key', 'hotel_email')->value('value')
            ?? config('mail.from.address');

        try {
            // Correo para el administrador
            Mail::to($adminEmail)->send(new BookingRequestMail($booking));

            // Correo para el cliente (Confirmación)
            Mail::to($booking->email)->send(new BookingConfirmationMail($booking));
            \Log::info('Correos de reserva enviados con éxito.');
        } catch (\Exception $e) {
            \Log::error('Error enviando correos de reserva: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Tu solicitud ha sido enviada con éxito. Nos pondremos en contacto contigo pronto para confirmar disponibilidad y costos.');
    }
}
