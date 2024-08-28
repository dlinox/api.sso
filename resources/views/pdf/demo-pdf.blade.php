<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo pdf</title>
    <style>
        .table{
            
        }
    </style>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>N° Reporte</th>

                <th>Nombre </th>
                <th>N° Doc.</th>
                <th>Codigo</th>
                <th> Escuela / Oficina </th>
                <th>Tipo de atención </th>
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


</body>

</html>