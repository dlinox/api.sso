<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\Report;
use App\Models\TypeAttention;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    protected $attentions_view;

    protected $attention;

    public function __construct()
    {
        $this->attentions_view = DB::table('attentions_view');
        $this->attention = new Attention();
    }


    //reporte 1: Grafico de atenciones por mes de un año en especifico


    public function attentionsByMonth($year)
    {


        $data = $this->attentions_view
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get();

        //que se aun array de 12 posiciones con los meses y la cantidad de atenciones

        $months = array_fill(1, 12, 0);

        foreach ($data as $item) {
            $months[$item->month] = $item->total;
        }

        $months = array_values($months);
        return response()->json($months);
    }

    public function attentionsByMonthType($year)
    {

        $results = DB::table('attentions')
            ->selectRaw('person_type, MONTH(created_at) as month, COUNT(id) as counts')
            ->groupBy('person_type', DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();

        $monthlyCountsByPersonType = [];
        foreach ($results as $result) {
            $personType = $result->person_type;
            $month = (int) $result->month;
            $count = (int) $result->counts;
            if (!isset($monthlyCountsByPersonType[$personType])) {
                $monthlyCountsByPersonType[$personType] = array_fill(1, 12, 0);
            }
            $monthlyCountsByPersonType[$personType][$month] = $count;
        }

        foreach ($monthlyCountsByPersonType as $personType => $counts) {
            $monthlyCountsByPersonType[$personType] = array_values($counts);
        }

        return response()->json($monthlyCountsByPersonType);
    }

    public function rerportPdf(Request $request)
    {

        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 54.5,
                'margin_bottom' => 15,
                'default_font' => 'arial',
                'header_border' => 'none',
            ]);

            $userName = null;
            $tyepAttention = null;
            $dates = null;

            $nextNumber = Report::nextNumber('I01', date('Y'));
            $title = 'INFORME N° ' . $nextNumber . '-' . date('Y') . '-SUSS-DBU-UNA';

            if ($request->filters) {
                if (isset($request->filters['user_id'])) {
                    $user = User::select(DB::raw("concat_ws(' ', name, paternal_surname,maternal_surname) as name"))
                        ->where('id', $request->filters['user_id'])
                        ->first();
                    $userName = $user->name;
                }
                if (isset($request->filters['type_attention_id'])) {
                    $ta = TypeAttention::find($request->filters['type_attention_id']);
                    $tyepAttention = $ta->name;
                }
                //ambas fechas
                if (isset($request->filters['start_date']) && isset($request->filters['end_date'])) {
                    $dates = 'Desde ' . $request->filters['start_date'] . ' hasta ' . $request->filters['end_date'];
                }
            }

            $data = [
                'data' => $request->data,
                'userName' => $userName,
            ];

            $dataHeader = [
                'userName' => $userName,
                'tyepAttention' => $tyepAttention,
                'title' => $title,
                'dates' => $dates,
            ];

            $content = view('pdf.attentions.content', $data)->render() . PHP_EOL;
            $header = view('pdf.attentions._header', $dataHeader)->render() . PHP_EOL;
            $footer = view('pdf.attentions._footer')->render() . PHP_EOL;

            $mpdf->SetHeader($header);
            $mpdf->SetFooter($footer);
            $mpdf->WriteHTML($content);


            $report = new Report();
            $report->name = $title;
            $report->type = 'I01';
            $report->number = $nextNumber;
            $report->year = date('Y');
            $report->payload = json_encode($request->all());
            $report->user_id = Auth::user()->id;
            $report->save();

            return response($mpdf->Output('reporte.pdf', 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="reporte.pdf"');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function ratingForUser(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $query = DB::table('users as u')
            ->leftJoin('satisfaction_surveys as sa', 'sa.user_id', '=', 'u.id')
            ->select(
                'u.id',
                'u.name',
                'u.paternal_surname',
                'u.maternal_surname',
                'u.email',
                DB::raw('COUNT(sa.id) as total_surveys'),
                DB::raw('AVG(sa.score) as average_score'),
                DB::raw('SUM(CASE WHEN sa.score = 5 THEN 1 ELSE 0 END) as five_score'),
                DB::raw('SUM(CASE WHEN sa.score = 4 THEN 1 ELSE 0 END) as four_score'),
                DB::raw('SUM(CASE WHEN sa.score = 3 THEN 1 ELSE 0 END) as three_score'),
                DB::raw('SUM(CASE WHEN sa.score = 2 THEN 1 ELSE 0 END) as two_score'),
                DB::raw('SUM(CASE WHEN sa.score = 1 THEN 1 ELSE 0 END) as one_score'),
                DB::raw('SUM(CASE WHEN sa.score IS NULL THEN IF(sa.id IS NULL, 0, 1) ELSE 0 END) AS no_score')
            )
            ->groupBy('u.id')
            ->orderBy('average_score', 'DESC');

        // Si hay fechas, aplicamos los filtros de rango
        if ($start_date && $end_date) {
            $query->whereBetween('sa.created_at', [$start_date, $end_date]);
        }

        // Ejecutar la consulta
        $response = $query->get();


        return response()->json($response);
    }

    //rerportUserPdf
    public function rerportUserPdf(Request $request)
    {
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 54.5,
                'margin_bottom' => 15,
                'default_font' => 'arial',
                'header_border' => 'none',
            ]);

            $userName = null;
            //el nombre del usuario que genero el reporte
            $user = Auth::user();
            $userName = $user->name . ' ' . $user->paternal_surname . ' ' . $user->maternal_surname;

            $nextNumber = Report::nextNumber('I02', date('Y'));
            $title = 'INFORME N° ' . $nextNumber . '-' . date('Y') . '-SUSS-DBU-UNA';

            $data = [
                'data' => $request->data,
                'userName' => $userName,
            ];

            $dataHeader = [
                'userName' => $userName,
                'title' => $title,
            ];

            $content = view('pdf.users.content', $data)->render() . PHP_EOL;
            $header = view('pdf.users._header', $dataHeader)->render() . PHP_EOL;
            $footer = view('pdf.users._footer')->render() . PHP_EOL;

            $mpdf->SetHeader($header);
            $mpdf->SetFooter($footer);
            $mpdf->WriteHTML($content);


            $report = new Report();
            $report->name = $title;
            $report->type = 'I02';
            $report->number = $nextNumber;
            $report->year = date('Y');
            $report->payload = json_encode($request->all());
            $report->user_id = Auth::user()->id;
            $report->save();

            return response($mpdf->Output('reporte.pdf', 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="reporte.pdf"');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
