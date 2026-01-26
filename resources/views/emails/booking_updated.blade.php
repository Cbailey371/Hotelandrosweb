<!DOCTYPE html>
<html>

<head>
    <title>Actualización de Reserva / Booking Update</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333; background-color: #f9fafb; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 20px auto; padding: 40px; background: white; border-radius: 12px; border: 1px solid #e7edf3; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #137fec; margin: 0; font-size: 24px;">¡Hola / Hello, {{ $booking->customer_name }}!</h1>
            <p style="font-size: 16px; color: #64748b;">Tu solicitud de reserva en <strong>Hotel Andros</strong> ha sido
                actualizada.<br>
                <span style="font-size: 14px;">Your booking request at <strong>Hotel Andros</strong> has been
                    updated.</span>
            </p>
        </div>

        <p style="border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px;">
            Te informamos que se han realizado cambios en los detalles o el costo de tu reserva. A continuación
            encontrarás el resumen actualizado.
        </p>
        <p style="color: #64748b; font-style: italic; font-size: 15px;">
            We are informing you that changes have been made to the details or cost of your booking. Below you will find
            the updated summary.
        </p>

        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Detalles
                Actualizados / Updated Details</h3>
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
                        (${{ number_format($booking->base_price, 2) }} x {{ $booking->nights ?? 1 }} noches) / Base Rate
                        (${{ number_format($booking->base_price, 2) }} x {{ $booking->nights ?? 1 }} nights):</td>
                    <td style="padding: 8px 0; text-align: right;">
                        ${{ number_format($booking->base_price * ($booking->nights ?? 1), 2) }}</td>
                </tr>
                @if($booking->extra_person_total > 0)
                    <tr>
                        <td style="padding: 8px 0; color: #475569;">Cargos Personas Extra / Extra Guest Charges:</td>
                        <td style="padding: 8px 0; text-align: right;">${{ number_format($booking->extra_person_total, 2) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #475569;">Impuestos / Taxes:</td>
                    <td style="padding: 8px 0; text-align: right;">${{ number_format($booking->tax_amount, 2) }}</td>
                </tr>
                <tr style="border-top: 2px solid #e2e8f0;">
                    <td style="padding: 15px 0 0; font-weight: bold; color: #0f172a; font-size: 18px;">Nuevo Total / New
                        Total:</td>
                    <td
                        style="padding: 15px 0 0; text-align: right; font-weight: bold; color: #137fec; font-size: 18px;">
                        ${{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <p style="margin: 0; font-weight: bold; color: #1e293b;">Hotel Andros</p>
            <p style="margin: 5px 0 0; font-size: 12px; color: #94a3b8;">Panamá, Colon</p>
        </div>
    </div>
</body>

</html>