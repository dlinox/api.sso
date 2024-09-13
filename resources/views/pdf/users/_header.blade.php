<div sytle="width: 100%; height: 100px; text-align: center;">
    <table style="width: 100%;">
        <tr style="width: 100%;">
            <td class="left-logo" style="text-align: left;">
                <img src="{{ public_path('sso-logo.jpg') }}" alt="Logo SUSS" width="80">
            </td>
            <td class="left-text" style="text-align: center;">
                <h3>Universidad Nacional del Aliplano Puno</h3>
                <h4>
                    Dirección de Bienestar Universitario
                </h4>
                <h4>
                    Sub Unidad Servicio Social
                </h4>
            </td>
            <td class="right-logo" style="text-align: right;">
                <img src="{{ public_path('unap-logo.png') }}" alt="Logo UNAP" width="80">
            </td>
        </tr>

        <tr style="width: 100%;">
            <td colspan="3" style="text-align: center;">
                <h3>
                    {{$title}}
                </h3>
            </td>
        </tr>
        <tr style="width: 100%;">
            <td colspan="3" style="text-align: start;">
                <p>
                    <b> Generado por: </b> {{$userName ?? 'No especificado'}}
                </p>
            </td>
        </tr>
    </table>
</div>