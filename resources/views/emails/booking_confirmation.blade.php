<!DOCTYPE html>
<html>

<head>
    <title>Confirmación de solicitud de reserva</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333; background-color: #f9fafb; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 20px auto; padding: 40px; background: white; border-radius: 12px; border: 1px solid #e7edf3; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #137fec; margin: 0; font-size: 24px;">¡Hola / Hello, {{ $booking->customer_name }}!</h1>
            <p style="font-size: 16px; color: #64748b;">Hemos recibido tu solicitud de reserva en <strong>Hotel
                    Andros</strong>.<br>
                <span style="font-size: 14px;">We have received your booking request at <strong>Hotel
                        Andros</strong>.</span>
            </p>
        </div>

        @php
            $bodyEs = \App\Models\Setting::where('key', 'mail_confirmation_body')->value('value')
                ?? 'Gracias por elegirnos. A continuación te presentamos el resumen de tu solicitud. Nos pondremos en contacto contigo a la brevedad para confirmar la disponibilidad y finalizar tu reserva.';

            $bodyEn = \App\Models\Setting::where('key', 'mail_confirmation_body_en')->value('value')
                ?? 'Thank you for choosing us. Below is the summary of your request. We will contact you shortly to confirm availability and finalize your booking.';

            $placeholders = [
                '{cliente}' => $booking->customer_name,
                '{habitacion}' => $booking->room->name_es,
                '{check_in}' => $booking->check_in,
                '{check_out}' => $booking->check_out,
                '{huespedes}' => $booking->guests,
                '{referencia}' => '#BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
                '{email}' => $booking->email,
                '{telefono}' => $booking->phone ?? 'N/A',
                '{pais}' => $booking->country ?? 'N/A',
                '{mensaje}' => $booking->message ?? '',
                '{hotel}' => \App\Models\Setting::where('key', 'hotel_name')->value('value') ?? 'Hotel Andros',
            ];

            $bodyEs = str_replace(array_keys($placeholders), array_values($placeholders), $bodyEs);
            $bodyEn = str_replace(array_keys($placeholders), array_values($placeholders), $bodyEn);
            $footerLocation = \App\Models\Setting::where('key', 'mail_footer_location')->value('value') ?? 'Panamá, Colon';
        @endphp

        <p style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px;">
            {!! nl2br(e($bodyEs)) !!}
        </p>
        <p style="color: #64748b; font-style: italic; font-size: 15px;">{!! nl2br(e($bodyEn)) !!}</p>

        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Detalles
                de la Solicitud / Request Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Referencia / Reference:</td>
                    <td style="padding: 8px 0; text-align: right;">#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Habitación / Room:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ $booking->room->name_es }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Cant. Habitaciones / Number of Rooms:
                    </td>
                    <td style="padding: 8px 0; text-align: right;">{{ $booking->number_of_rooms ?? 1 }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Huéspedes / Guests:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ $booking->guests }} persona(s)</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Check-in:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ $booking->check_in }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Check-out:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ $booking->check_out }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Noches / Nights:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ $booking->nights ?? 1 }}</td>
                </tr>
            </table>
        </div>

        <div
            style="background-color: #ffffff; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e2e8f0;">
            <h3 style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Desglose
                de Cargos / Price Breakdown</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #475569;">Tarifa Base
                        (${{ number_format($booking->base_price ?? $booking->room->price, 2) }} x
                        {{ $booking->nights ?? 1 }} noches x {{ $booking->number_of_rooms ?? 1 }}
                        {{ Str::plural('hab', $booking->number_of_rooms ?? 1) }}) / Base Rate
                        (${{ number_format($booking->base_price ?? $booking->room->price, 2) }} x
                        {{ $booking->nights ?? 1 }} nights x {{ $booking->number_of_rooms ?? 1 }}
                        {{ Str::plural('room', $booking->number_of_rooms ?? 1) }}):
                    </td>
                    <td style="padding: 8px 0; text-align: right;">
                        ${{ number_format(($booking->base_price ?? $booking->room->price) * ($booking->nights ?? 1) * ($booking->number_of_rooms ?? 1), 2) }}
                    </td>
                </tr>
                @if(($booking->extra_person_total ?? 0) > 0)
                    <tr>
                        <td style="padding: 8px 0; color: #475569;">Cargos Personas Extra / Extra Guest Charges:</td>
                        <td style="padding: 8px 0; text-align: right;">${{ number_format($booking->extra_person_total, 2) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #475569;">Impuestos (ITBMS
                        {{ $booking->room->tax_percentage ?? 7 }}%) / Taxes:
                    </td>
                    <td style="padding: 8px 0; text-align: right;">${{ number_format($booking->tax_amount ?? 0, 2) }}
                    </td>
                </tr>
                <tr style="border-top: 2px solid #e2e8f0;">
                    <td style="padding: 15px 0 0; font-weight: bold; color: #0f172a; font-size: 18px;">Total de la
                        Solicitud / Total Amount:</td>
                    <td
                        style="padding: 15px 0 0; text-align: right; font-weight: bold; color: #137fec; font-size: 18px;">
                        ${{ number_format($booking->total_amount ?? 0, 2) }}</td>
                </tr>
            </table>
            <p style="font-size: 11px; color: #94a3b8; margin-top: 15px; font-style: italic;">* Los precios están
                expresados en USD. | Prices are in USD.</p>
        </div>

        @php
            $footerNoteEs = \App\Models\Setting::where('key', 'mail_footer_note')->value('value')
                ?? 'Si tienes alguna duda, puedes responder directamente a este correo o contactarnos por los canales oficiales del hotel.';

            $footerNoteEn = \App\Models\Setting::where('key', 'mail_footer_note_en')->value('value')
                ?? 'If you have any questions, you can reply directly to this email or contact us through the hotel official channels.';
        @endphp

        <p style="color: #64748b; font-size: 14px; text-align: center; margin-bottom: 5px;">{{ $footerNoteEs }}</p>
        <p style="color: #94a3b8; font-size: 13px; text-align: center; font-style: italic;">{{ $footerNoteEn }}</p>

        <div style="margin-top: 30px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <p style="margin: 0; font-weight: bold; color: #1e293b;">Hotel Andros</p>
            <p style="margin: 5px 0 0; font-size: 12px; color: #94a3b8;">{{ $footerLocation }}</p>
        </div>
    </div>
</body>

</html>