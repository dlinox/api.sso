<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;


Route::get('/', function () {
    return view('welcome');
});
