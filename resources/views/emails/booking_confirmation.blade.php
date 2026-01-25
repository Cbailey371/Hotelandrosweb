<!DOCTYPE html>
<html>

<head>
    <title>Confirmación de solicitud de reserva</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333; background-color: #f9fafb; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 20px auto; padding: 40px; background: white; border-radius: 12px; border: 1px solid #e7edf3; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #137fec; margin: 0; font-size: 24px;">¡Hola, {{ $booking->customer_name }}!</h1>
            <p style="font-size: 16px; color: #64748b;">Hemos recibido tu solicitud de reserva en <strong>Hotel
                    Andros</strong>.</p>
        </div>

        @php
            $body = \App\Models\Setting::where('key', 'mail_confirmation_body')->value('value')
                ?? 'Gracias por elegirnos. A continuación te presentamos el resumen de tu solicitud. Nos pondremos en contacto contigo a la brevedad para confirmar la disponibilidad y finalizar tu reserva.';

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

            $body = str_replace(array_keys($placeholders), array_values($placeholders), $body);
            $footerLocation = \App\Models\Setting::where('key', 'mail_footer_location')->value('value') ?? 'Panamá, Colon';
        @endphp

        <p>{!! nl2br(e($body)) !!}</p>

        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Detalles
                de la Solicitud</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Habitación:</td>
                    <td style="padding: 8px 0;">{{ $booking->room->name_es }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Huéspedes:</td>
                    <td style="padding: 8px 0;">{{ $booking->guests }} persona(s)</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Check-in:</td>
                    <td style="padding: 8px 0;">{{ $booking->check_in }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #475569;">Check-out:</td>
                    <td style="padding: 8px 0;">{{ $booking->check_out }}</td>
                </tr>
            </table>
        </div>

        <p style="color: #64748b; font-size: 14px; text-align: center;">Si tienes alguna duda, puedes responder
            directamente a este correo o contactarnos por los canales oficiales del hotel.</p>

        <div style="margin-top: 30px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <p style="margin: 0; font-weight: bold; color: #1e293b;">Hotel Andros</p>
            <p style="margin: 5px 0 0; font-size: 12px; color: #94a3b8;">{{ $footerLocation }}</p>
        </div>
    </div>
</body>

</html>