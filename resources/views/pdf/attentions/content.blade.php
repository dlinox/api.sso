<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
</head>

<body>
    <div style="width: 100%; font-family: Arial, sans-serif;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="width: 100%;">
                <tr style="width: 100%; background-color: #f2f2f2;">
                    <th style=" border: 1px solid #999; padding: 5px;">Item</th>
                    <th style=" border: 1px solid #999; padding: 5px;">N° Reporte</th>
                    <th style=" border: 1px solid #999; padding: 5px;">Nombre</th>
                    <th style=" border: 1px solid #999; padding: 5px;">N° Doc.</th>
                    <th style=" border: 1px solid #999; padding: 5px;">Código</th>
                    <th style=" border: 1px solid #999; padding: 5px;">Escuela / Oficina</th>
                    <th style=" border: 1px solid #999; padding: 5px;">Tipo de atención</th>
                    <th style=" border: 1px solid #999; padding: 5px;">Fecha de atención</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $val)
                    <tr>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $index + 1 }}</td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['report_number'] }}</td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['person_name'] }}</td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['person_document'] }}
                        </td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['person_code'] }}</td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['unit_name'] }}</td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['type_attention_name'] }}
                        </td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $val['created_at'] }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        @if($userName)


            <table style="width: 100%; border-collapse: collapse; margin-top: 150px ;">
                <tr>
                    <td style="width: 65%;">
                    </td>
                    <td style="width: 35%; border-top: 1px solid #000; text-align:center;">
                        {{$userName}}
                    </td>
                </tr>
            </table>
        @endif
    </div>
</body>

</html>