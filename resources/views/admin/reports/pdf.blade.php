<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de Reservas - Hotel Andros</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #137fec;
        }

        .meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }

        .kpi-container {
            margin-bottom: 20px;
        }

        .kpi-box {
            display: inline-block;
            width: 30%;
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-right: 2%;
        }

        .kpi-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
        }

        .kpi-value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background-color: #137fec;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            border-bottom: 1px solid #eee;
            padding: 8px;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .status-confirmed {
            color: green;
        }

        .status-cancelled {
            color: red;
        }

        .status-pending {
            color: orange;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: center;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">Hotel Andros</div>
        <p>Reporte de Reservas</p>
    </div>

    <div class="meta">
        <strong>Generado por:</strong> {{ $generated_by }}<br>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($generated_at)->format('d/m/Y H:i') }}<br>
        <strong>Filtros:</strong>
        {{ $filters['start_date'] ?? 'Inicio' }} - {{ $filters['end_date'] ?? 'Fin' }} |
        Estado: {{ ucfirst($filters['status'] ?? 'Todos') }}
    </div>

    <div class="kpi-container">
        <div class="kpi-box">
            <div class="kpi-label">Ingresos</div>
            <div class="kpi-value">${{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Reservas</div>
            <div class="kpi-value">{{ $totalBookings }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Confirmadas</div>
            <div class="kpi-value">{{ $confirmedBookings }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Habitación</th>
                <th>Estado</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</td>
                    <td>{{ $booking->room->name ?? '-' }}</td>
                    <td>
                        <span class="status status-{{ $booking->status }}">{{ $booking->status }}</span>
                    </td>
                    <td style="text-align: right;">${{ number_format($booking->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de administración de Hotel Andros.
    </div>
</body>

</html>