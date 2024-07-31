<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\External;
use App\Models\Office;
use App\Models\Professor;
use App\Models\Student;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentionController extends Controller
{

    public function index()
    {
        $attentions = Attention::latest()->get();
        return response()->json($attentions);
    }

    public function report()
    {
        $attentions = Attention::select(
            'attentions.id',
            'attentions.report_number',
            'attentions.person_id',
            'attentions.description',
            'attentions.person_type',
            'type_attentions.name as type_attention_name',
            'attentions.created_at',
            DB::raw("case attentions.person_type
            when '001' then (select CONCAT(name,' ',paternal_surname) from students where id = attentions.person_id)
            when '002' then (select CONCAT(name,' ',paternal_surname) from professors where id = attentions.person_id)
            when '003' then (select CONCAT(name,' ',paternal_surname) from workers where id = attentions.person_id)
            when '004' then (select CONCAT(name,' ',paternal_surname) from externals where id = attentions.person_id)
            end as person_name"),
            DB::raw("case attentions.person_type
            when '001' then (select name from careers where code =  (select career_code from students where id = attentions.person_id))
            when '002' then (select name from careers where code =  (select career_code from professors where id = attentions.person_id))
            when '003' then ''
            when '004' then ''
            end as career_name"),
            DB::raw("case attentions.person_type
            when '001' then ''
            when '002' then ''
            when '003' then (select name from offices where id = (select office_id from workers where id = attentions.person_id))
            when '004' then ''
            end as office_name"),
        )
            ->join('type_attentions', 'type_attentions.id', '=', 'attentions.type_attention_id')
            ->orderBy('attentions.created_at', 'desc')
            ->get();

        return response()->json($attentions);
    }

    public function last()
    {
        $attentions = Attention::select(
            'attentions.id',
            'attentions.report_number',
            'attentions.person_id',
            'attentions.description',
            'attentions.person_type',
            'type_attentions.name as type_attention_name',
            'attentions.created_at'
        )
            ->join('type_attentions', 'type_attentions.id', '=', 'attentions.type_attention_id')
            ->orderBy('attentions.created_at', 'desc')
            ->limit(15)->get()->map(function ($attention) {
                //name of the type of attention
                if ($attention->person_type == '001') {
                    $attention->person_name = Student::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                } else if ($attention->person_type == '002') {
                    $attention->person_name = Professor::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                } else if ($attention->person_type == '003') {
                    $attention->person_name = Worker::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                } else if ($attention->person_type == '004') {
                    $attention->person_name = External::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                }

                return $attention;
            });
        return response()->json($attentions);
    }



    public function store(Request $request, $type)
    {

        $request->validate(
            [
                'report_number' => 'required|max:255',
                'description' => 'required',
                'person_id' => 'required|integer',
                'type_attention_id' => 'required|integer',
            ],
            [
                'report_number.required' => 'El número de reporte es obligatorio',
                'description.required' => 'La descripción es obligatoria',
                'type_attention_id.required' => 'El tipo de atención es obligatorio',
            ]
        );
        try {
            $request['person_type'] = $type;
            $request['user_id'] = auth()->user()->id;
            $request['derivations'] = implode(',', $request->derivate_to);
            $attention = Attention::create($request->only('report_number', 'description', 'derivations', 'person_id', 'type_attention_id', 'user_id', 'person_type'));
            return response()->json($attention);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function offices()
    {
        $offices = Attention::select('offices.name as text', 'offices.name as value')
            ->join('workers', 'workers.id', '=', 'attentions.person_id')
            ->join('offices', 'offices.id', '=', 'workers.office_id')
            ->where('attentions.person_type', '003')
            ->groupBy('offices.name')
            ->get();
        return response()->json($offices);
    }
}
