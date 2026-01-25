<!DOCTYPE html>
<html>

<head>
    <title>Reserva Confirmada - Hotel Andros</title>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f1f5f9; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 40px auto; padding: 0; background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #137fec 0%, #0b5fb3 100%); padding: 40px; text-align: center;">
            <div
                style="display: inline-block; padding: 12px; background: rgba(255,255,255,0.2); border-radius: 16px; margin-bottom: 20px;">
                <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" width="40" height="40" alt="Check"
                    style="display: block;">
            </div>
            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.025em;">¡Reserva
                Confirmada!</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin-top: 10px; font-weight: 500;">Estamos listos
                para recibirte en Hotel Andros.</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px;">
            @php
                $body = \App\Models\Setting::where('key', 'mail_processed_body')->value('value')
                    ?? 'Es un placer informarte que tu solicitud de reserva ha sido procesada y confirmada por nuestro equipo de recepción. A continuación encontrarás los detalles finales de tu estancia:';

                $placeholders = [
                    '{cliente}' => $booking->customer_name,
                    '{habitacion}' => $booking->room->name_es,
                    '{check_in}' => \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y'),
                    '{check_out}' => \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y'),
                    '{huespedes}' => $booking->guests,
                ];

                $body = str_replace(array_keys($placeholders), array_values($placeholders), $body);
                $footerLocation = \App\Models\Setting::where('key', 'mail_footer_location')->value('value') ?? 'Panamá, Colon';
            @endphp

            <p style="font-size: 16px; margin-top: 0;">Hola <strong>{{ $booking->customer_name }}</strong>,</p>
            <p style="font-size: 16px;">{!! nl2br(e($body)) !!}</p>

            <div
                style="background-color: #f8fafc; padding: 30px; border-radius: 20px; margin: 30px 0; border: 1px solid #e2e8f0;">
                <h3
                    style="margin-top: 0; color: #0f172a; font-size: 18px; font-weight: 700; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;">
                    Resumen de tu Estancia</h3>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td
                            style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                            Referencia:</td>
                        <td style="padding: 10px 0; color: #0f172a; font-weight: 700; text-align: right;">
                            #BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                            Habitación:</td>
                        <td style="padding: 10px 0; color: #0f172a; font-weight: 700; text-align: right;">
                            {{ $booking->room->name_es }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                            Huéspedes:</td>
                        <td style="padding: 10px 0; color: #0f172a; font-weight: 700; text-align: right;">
                            {{ $booking->guests }} Persona(s)
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                            Check-in:</td>
                        <td style="padding: 10px 0; color: #0f172a; font-weight: 700; text-align: right;">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                            Check-out:</td>
                        <td style="padding: 10px 0; color: #0f172a; font-weight: 700; text-align: right;">
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div
                style="background-color: #fefce8; padding: 20px; border-radius: 12px; border: 1px solid #fef08a; margin-bottom: 30px;">
                <p style="margin: 0; font-size: 14px; color: #854d0e; font-weight: 500;">
                    <strong>Nota importante:</strong> Recuerda presentar tu documento de identidad al momento de tu
                    llegada. Si tienes algún requerimiento de última hora, no dudes en contactarnos.
                </p>
            </div>

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}"
                    style="display: inline-block; padding: 16px 32px; background-color: #137fec; color: white; text-decoration: none; border-radius: 14px; font-weight: 700; font-size: 16px; box-shadow: 0 10px 15px -3px rgba(19, 127, 236, 0.3);">Visitar
                    Sitio Web</a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0; font-weight: 700; color: #0f172a; font-size: 16px;">Hotel Andros</p>
            <p style="margin: 5px 0 0; font-size: 13px; color: #64748b; font-weight: 500;">{{ $footerLocation }}</p>
            <div style="margin-top: 20px;">
                <p style="margin: 0; font-size: 12px; color: #94a3b8;">&copy; {{ date('Y') }} Hotel Andros. Todos los
                    derechos reservados.</p>
            </div>
        </div>
    </div>
</body>

</html>