<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Recuperar contraseña</title></head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h1>Sport Club</h1>
    <h2>Recupera tu contraseña</h2>
    <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta.</p>
    <p><a href="{{ $resetUrl }}" style="display: inline-block; background: #0d6efd; color: #fff; padding: 12px 18px; text-decoration: none; border-radius: 4px;">Restablecer contraseña</a></p>
    <p>Este enlace vencerá en {{ $expiresIn }} minutos.</p>
    <p>Si no solicitaste cambiar tu contraseña, puedes ignorar este correo.</p>
    <p>Si el botón no funciona, copia y pega este enlace en tu navegador:<br>{{ $resetUrl }}</p>
    <p>Equipo Sport Club</p>
</body>
</html>
