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
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">Item</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">Nombre</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">Correo</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">Total Atenciones</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">Puntuación Promedio</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">5</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">4</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">3</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">2</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">1</th>
                    <th style="font-size: 12px; border: 1px solid #999; padding: 5px;">Sin Calificar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $val)
                    <tr>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">{{ $index + 1 }}</td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['name'] }} {{ $val['paternal_surname'] }} {{ $val['maternal_surname'] }}

                        </td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['email'] }}
                        </td>

                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['total_surveys'] }}
                        </td>

                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['average_score'] }}
                        </td>

                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['five_score'] }}
                        </td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['four_score'] }}
                        </td>

                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['three_score'] }}

                        </td>

                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['two_score'] }}

                        </td>

                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['one_score'] }}
                        </td>
                        <td style="font-size: 11px; border: 1px solid #999; padding: 5px;">
                            {{ $val['no_score'] }}
                        </td>
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