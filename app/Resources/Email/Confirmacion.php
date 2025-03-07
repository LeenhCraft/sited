<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirme su dirección de correo electrónico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .header img {
            max-width: 150px;
            height: auto;
        }

        .content {
            padding: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= base_url() . "img/logo.png" ?>" alt="Logo">
            <h1>Confirmar Email</h1>
        </div>
        <div class="content">
            <p>Hola <strong><?= $data["nombre"] ?? '{{nombre}}' ?></strong>,</p>
            <p>Gracias por registrarte. Por favor, confirma tu dirección de correo electrónico haciendo clic en el siguiente botón:</p>
            <p style="text-align: center;">
                <a href="<?= $data["url_recovery"] ?? '{{url_recovery}}' ?>" class="button">Confirmar correo electrónico</a>
            </p>
            <p>Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
            <p style="word-break: break-all; margin: 15px 0; padding: 10px; background-color: #f8f8f8; border-radius: 4px;">
                <?= $data["url_recovery"] ?? '{{url_recovery}}' ?>
            </p>
            <p>Este enlace expirará en 24 horas.</p>
            <p>Si no has solicitado este correo, puedes ignorarlo.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Tu Empresa. Todos los derechos reservados.</p>
            <p>Este correo fue enviado a <?= $data["email"] ?? '{{email}}' ?></p>
        </div>
    </div>
</body>

</html>