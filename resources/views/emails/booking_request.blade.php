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
                    {{ $booking->country ?? 'No especificado' }}
                </td>
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
                <td style="padding: 8px 0; font-weight: bold; border-bottom: 1px solid #f0f2f5;">Noches:</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f0f2f5;">{{ $booking->nights ?? 1 }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Mensaje:</td>
                <td style="padding: 8px 0;">{{ $booking->message ?? 'Sin mensaje' }}</td>
            </tr>
        </table>

        <!-- Nuevo Desglose de Precios para el Hotel -->
        <div
            style="margin-top: 30px; padding: 20px; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h3
                style="margin-top: 0; color: #0f172a; font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px;">
                Desglose Económico</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Tarifa Base
                        (${{ number_format($booking->base_price ?? $booking->room->price, 2) }} x
                        {{ $booking->nights ?? 1 }} noches):</td>
                    <td style="padding: 6px 0; text-align: right; font-weight: bold;">
                        ${{ number_format(($booking->base_price ?? $booking->room->price) * ($booking->nights ?? 1), 2) }}
                    </td>
                </tr>
                @if(($booking->extra_person_total ?? 0) > 0)
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Cargos Personas Extra:</td>
                        <td style="padding: 6px 0; text-align: right; font-weight: bold;">
                            ${{ number_format($booking->extra_person_total, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Impuestos
                        ({{ $booking->room->tax_percentage ?? 7 }}%):</td>
                    <td style="padding: 6px 0; text-align: right; font-weight: bold;">
                        ${{ number_format($booking->tax_amount ?? 0, 2) }}</td>
                </tr>
                <tr style="border-top: 2px solid #e2e8f0;">
                    <td style="padding: 12px 0 0; font-weight: bold; color: #0f172a; font-size: 16px;">TOTAL A COBRAR:
                    </td>
                    <td
                        style="padding: 12px 0 0; text-align: right; font-weight: bold; color: #137fec; font-size: 18px;">
                        ${{ number_format($booking->total_amount ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/admin/bookings') }}"
                style="background: #137fec; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;">Ver
                en el Panel</a>
        </div>
    </div>
</body>

</html>