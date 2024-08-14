<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        /*
         {
    name: "Estudiantes",
    data: [1, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0],
  },
  {
    name: "Docentes",
    data: [0, 0, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0],
  },
  {
    name: "Administrativos",
    data: [0, 0, 0, 40, 0, 0, 0, 50, 0, 0, 0, 0],
  },
        */


        $results = DB::table('attentions')
            ->selectRaw('person_type, MONTH(created_at) as month, COUNT(id) as counts')
            ->groupBy('person_type', DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();

        $monthlyCountsByPersonType = [];
        foreach ($results as $result) {
            $personType = $result->person_type;
            $month = (int)$result->month;
            $count = (int)$result->counts;
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
}
