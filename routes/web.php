<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadView('pdf.demo-pdf');
    return $pdf->stream();
});


Route::post('/pdf/report', [ReportController::class, 'rerportPdf']);
Route::get('/generar-pdf', function () {
    $mpdf = new Mpdf();

    // Definir el encabezado
    $mpdf->SetHeader('
        <table width="100%">
            <tr>
                <td width="33%">Logo de la empresa</td>
                <td width="33%" align="center">Encabezado del PDF</td>
                <td width="33%" style="text-align: right;">Página {PAGENO} de {nbpg}</td>
            </tr>
        </table>
    ');

    // Definir el pie de página
    $mpdf->SetFooter('
        <table width="100%">
            <tr>
                <td width="50%">Documento generado el {DATE j-m-Y}</td>
                <td width="50%" style="text-align: right;">www.miempresa.com</td>
            </tr>
        </table>
    ');

    // Contenido del PDF (puedes usar HTML aquí)
    $html = '
        <h1>Hola, mundo!</h1>
        <p>Este es un ejemplo de PDF con encabezado y pie de página usando mPDF en una ruta de Laravel.</p>
    ';

    $mpdf->WriteHTML($html);

    // Generar el PDF y devolverlo como respuesta para descarga
    return $mpdf->Output('mi_pdf.pdf', 'D');
});