<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo PDF</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            height: 100px;
            text-align: center;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            width: 50%;
        }

        .header-table .left-logo {
            text-align: left;
        }

        .header-table .right-logo {
            text-align: right;
        }

        .table-container {

            padding-top: 120px;
            /* Ajuste para la cabecera */
            padding-bottom: 60px;
            /* Ajuste para el pie de página */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <!-- Cabecera con logos -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="left-logo">
                    <img src="{{ public_path('sso-logo.jpg') }}" alt="Logo 1" width="100">
                </td>
                <td class="right-logo">
                    <img src="{{ public_path('unap-logo.png') }}" alt="Logo 2" width="100">
                </td>
            </tr>
        </table>
    </div>

    <!-- Contenedor de tabla para ajustar la posición relativa -->
    <div class="table-container">
        <!-- Tabla de datos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>N° Reporte</th>
                    <th>Nombre</th>
                    <th>N° Doc.</th>
                    <th>Código</th>
                    <th>Escuela / Oficina</th>
                    <th>Tipo de atención</th>
                    <th>Fecha de atención</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $val)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $val['report_number'] }}</td>
                    <td>{{ $val['person_name'] }}</td>
                    <td>{{ $val['person_document'] }}</td>
                    <td>{{ $val['person_code'] }}</td>
                    <td>{{ $val['unit_name'] }}</td>
                    <td>{{ $val['type_attention_name'] }}</td>
                    <td>{{ $val['created_at'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pie de página para paginación -->
    <div class="footer">
        Página <span class="pagenum"></span> de <span class="total-pages"></span>
    </div>

    <!-- Script para contar páginas -->
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                if ($PAGE_COUNT > 1 || $PAGE_COUNT == 1) {  // Mostrar numeración desde la primera página
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $size = 12;
                    $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
                    $y = 15;
                    $x = 520;
                    $pdf->text($x, $y, $pageText, $font, $size);
                }
            ');
        }
    </script>

</body>

</html>