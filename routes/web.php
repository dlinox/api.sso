<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadView('pdf.demo-pdf');
    return $pdf->stream();
});


Route::post('/pdf/report',  [ReportController::class, 'rerportPdf']);
