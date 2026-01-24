<!DOCTYPE html>
<html>

<head>
    <title>Nuevo Mensaje de Contacto</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e7edf3; border-radius: 12px;">
        <h2 style="color: #137fec;">Nuevo Mensaje de Contacto</h2>
        <p>Has recibido un nuevo mensaje a través del sitio web del hotel.</p>

        <div style="background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p><strong>De:</strong> {{ $data['name'] }} ({{ $data['email'] }})</p>
            <p><strong>Asunto:</strong> {{ $data['subject'] }}</p>
            <p><strong>Mensaje:</strong></p>
            <p style="white-space: pre-wrap;">{{ $data['message'] }}</p>
        </div>

        <hr style="border: 0; border-top: 1px solid #e7edf3; margin: 20px 0;">
        <p style="font-size: 12px; color: #4c739a;">Este es un mensaje automático enviado desde el formulario de
            contacto del sitio web.</p>
    </div>
</body>

</html>