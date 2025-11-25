<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contrato de Prestación de Servicios Educativos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            padding: 20px 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 10px;
        }

        .title {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .content {
            text-align: justify;
            margin-bottom: 10px;
        }

        .content p {
            margin-bottom: 8px;
            text-indent: 20px;
        }

        .clausula {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .two-columns {
            width: 100%;
            margin-top: 30px;
        }

        .two-columns td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }

        .column-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .column-content {
            text-align: center;
        }

        .signature-area {
            margin-top: 40px;
            text-align: center;
        }

        .signature-line {
            width: 250px;
            border-top: 1px solid #000;
            margin: 0 auto;
            padding-top: 5px;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
        }

        .footer-left {
            float: left;
            width: 60%;
        }

        .footer-right {
            float: right;
            width: 35%;
            text-align: right;
        }

        .qr-code {
            width: 100px;
            height: 100px;
        }

        .folio-info {
            font-size: 9pt;
            margin-top: 10px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(file_exists(public_path('images/logo-ceili.png')))
            <img src="{{ public_path('images/logo-ceili.png') }}" alt="CEILI" class="logo">
        @else
            <div style="font-size: 18pt; font-weight: bold; color: #003366; margin-bottom: 10px;">CEILI</div>
        @endif
    </div>

    <div class="title">
        CICLO ESCOLAR 2026 – 2027<br>
        CONTRATO DE PRESTACIÓN DE SERVICIOS EDUCATIVOS
    </div>

    <div class="content">
        <p>
            Contrato de Prestación de Servicios Educativos que celebran por una parte <span class="bold">{{ $nombre_proveedor }}</span>, 
            a quien en lo sucesivo se le denominará <span class="bold">"EL PROVEEDOR"</span> y por la otra el PADRE DE FAMILIA O TUTOR LEGAL 
            a quien en lo sucesivo se le denominará <span class="bold">"EL CONTRATANTE"</span>, al tenor de las siguientes declaraciones y cláusulas:
        </p>

        <p class="clausula">DECLARACIONES</p>

        <p>
            <span class="bold">I.</span> Declara <span class="bold">"EL PROVEEDOR"</span> que es una persona moral legalmente constituida 
            conforme a las leyes mexicanas, con capacidad jurídica para celebrar el presente contrato.
        </p>

        <p>
            <span class="bold">II.</span> Declara <span class="bold">"EL CONTRATANTE"</span> que es el padre, madre o tutor legal del menor 
            que recibirá los servicios educativos objeto del presente contrato.
        </p>

        <p class="clausula">CLÁUSULAS</p>

        <p>
            <span class="bold">PRIMERA.- Objeto del contrato.</span> "EL PROVEEDOR" se obliga a prestar los servicios educativos 
            correspondientes al ciclo escolar 2026-2027, de acuerdo con los planes y programas de estudio autorizados por la 
            Secretaría de Educación Pública.
        </p>

        <p>
            <span class="bold">SEGUNDA.- Obligaciones del proveedor.</span> "EL PROVEEDOR" se obliga a:
            a) Proporcionar los servicios educativos con calidad y eficiencia.
            b) Contar con el personal docente debidamente capacitado.
            c) Mantener las instalaciones en condiciones óptimas de seguridad e higiene.
            d) Informar oportunamente sobre el avance académico del alumno.
        </p>

        <p>
            <span class="bold">TERCERA.- Obligaciones del contratante.</span> "EL CONTRATANTE" se obliga a:
            a) Cubrir oportunamente las cuotas establecidas.
            b) Proporcionar al alumno los materiales necesarios para su educación.
            c) Asistir a las juntas y eventos escolares convocados.
            d) Informar cualquier situación que pueda afectar el desarrollo educativo del alumno.
        </p>

        <p>
            <span class="bold">CUARTA.- Cuotas y forma de pago.</span> Las cuotas por los servicios educativos serán las establecidas 
            por "EL PROVEEDOR" y deberán ser cubiertas en las fechas señaladas.
        </p>

        <p>
            <span class="bold">QUINTA.- Vigencia.</span> El presente contrato tendrá vigencia durante el ciclo escolar 2026-2027.
        </p>

        <p>
            <span class="bold">SEXTA.- Terminación anticipada.</span> Cualquiera de las partes podrá dar por terminado el presente 
            contrato mediante aviso por escrito con 30 días de anticipación.
        </p>

        <p>
            <span class="bold">SÉPTIMA.- Jurisdicción.</span> Para la interpretación y cumplimiento del presente contrato, las partes 
            se someten a la jurisdicción de los tribunales competentes.
        </p>

        <p>
            Leído que fue el presente contrato por las partes, lo firman de conformidad en la ciudad de Monterrey, Nuevo León, 
            a los <span class="bold">{{ $fecha }}</span>.
        </p>
    </div>

    <table class="two-columns">
        <tr>
            <td>
                <div class="column-title">EL PROVEEDOR</div>
                <div class="column-content">
                    <br><br><br>
                    <div class="signature-line">
                        {{ $nombre_proveedor }}
                    </div>
                </div>
            </td>
            <td>
                <div class="column-title">EL CONTRATANTE</div>
                <div class="column-content">
                    <div style="font-size: 9pt; margin-bottom: 5px;">PADRE DE FAMILIA O TUTOR LEGAL</div>
                    <br><br>
                    <div class="signature-line">
                        {{ $nombre_representante_legal }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer clearfix">
        <div class="footer-left">
            <div class="folio-info">
                <strong>Folio:</strong> {{ $folio }}<br>
                <strong>Fecha de registro:</strong> {{ $fecha }}
            </div>
        </div>
        <div class="footer-right">
            <img src="data:image/svg+xml;base64,{{ $qr_code }}" alt="QR Code" class="qr-code">
        </div>
    </div>
</body>
</html>
