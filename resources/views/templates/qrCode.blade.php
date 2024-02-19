<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Certificado Qr</title>
        <style>
            body {
                position: relative;
            }
            .container {
                position: absolute;
                bottom: 100px;
                left: 20px;
                max-width: 100%;
            }
            .text-string-code {
                width: 100%;
                word-wrap: break-word;
                display: block;
                font-size: 14px;
            }
            img {
                height: 100px;
                width: auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="data:image/png;base64, {!! $qrCode !!}">
            <span class="text-string-code">
                {{ $stringCode }}
            </span>
        </div>
    </body>
</html>
