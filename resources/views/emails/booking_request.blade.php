<!DOCTYPE html>
<html>

<head>
    <title>Nueva Solicitud de Reserva</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e7edf3; rounded: 12px;">
        <h2 style="color: #137fec;">Nueva Solicitud de Reserva</h2>
        <p>Has recibido una nueva consulta/solicitud desde la web:</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Cliente:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->customer_name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Email:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">País:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">
                    {{ $booking->country ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Teléfono:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->phone ?? 'No provisto' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Huéspedes:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->guests }} persona(s)</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Habitación:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->room->name_es }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Entrada:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->check_in }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Salida:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->check_out }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Mensaje:</td>
                <td style="padding: 8px 0;">{{ $booking->message ?? 'Sin mensaje' }}</td>
            </tr>
        </table>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/admin/bookings') }}"
                style="background: #137fec; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;">Ver
                en el Panel</a>
        </div>
    </div>
</body>

</html>