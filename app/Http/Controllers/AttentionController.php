<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\Career;
use App\Models\External;
use App\Models\Office;
use App\Models\Professor;
use App\Models\Student;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttentionController extends Controller
{
/*
SELECT 
    attentions.id,
    attentions.report_number,
    attentions.person_id,
    attentions.person_type,
    CASE
        WHEN attentions.person_type = '001' THEN 
            (SELECT CONCAT_WS(' ', s.name, s.paternal_surname) FROM students s WHERE s.id = attentions.person_id)
        WHEN attentions.person_type = '002' THEN 
            (SELECT CONCAT_WS(' ', p.name, p.paternal_surname) FROM professors p WHERE p.id = attentions.person_id)
        WHEN attentions.person_type = '003' THEN 
            (SELECT CONCAT_WS(' ', w.name, w.paternal_surname) FROM workers w WHERE w.id = attentions.person_id)
        WHEN attentions.person_type = '004' THEN 
            (SELECT CONCAT_WS(' ', e.name, e.paternal_surname) FROM externals e WHERE e.id = attentions.person_id)
    END AS person_name,
    CASE
        WHEN attentions.person_type = '001' THEN 
            (SELECT c.name FROM careers c WHERE c.code = (SELECT s.career_code FROM students s WHERE s.id = attentions.person_id))
        WHEN attentions.person_type = '002' THEN 
            (SELECT c.name FROM careers c WHERE c.code = (SELECT p.career_code FROM professors p WHERE p.id = attentions.person_id))
        WHEN attentions.person_type = '003' THEN 
            (SELECT o.name FROM offices o WHERE o.id = (SELECT w.office_id FROM workers w WHERE w.id = attentions.person_id))
        WHEN attentions.person_type = '004' THEN 'Externo'
    END AS unit_name,
    type_attentions.name AS type_attention_name,
    attentions.created_at
FROM 
    attentions
JOIN 
    type_attentions ON type_attentions.id = attentions.type_attention_id;
*/
    protected $attention;

    public function __construct()
    {
        $this->attention = new Attention();
    }


    public function items(Request $request)
    {

        $query = $this->attention->query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtros dinámicos
        if ($request->has('filters') && is_array($request->filters)) {
            foreach ($request->filters as $filter => $value) {
                // Aquí puedes agregar validaciones adicionales según sea necesario
                if (!is_null($value)) {
                    $query->where($filter, $value);
                }
            }
        }

        if ($request->has('sortBy') && is_array($request->sortBy)) {
            foreach ($request->sortBy as $sort) {
                $query->orderBy($sort['key'], $sort['order']);
            }
        }

        $perPage = $request->itemsPerPage == -1
            ? $query->count()
            : ($request->itemsPerPage ?? 10);

        $items = $query->latest()->paginate($perPage);
        return response()->json($items);
    }

    public function index()
    {
        $attentions = Attention::latest()->get();
        return response()->json($attentions);
    }

    public function getTodayAttentions()
    {
        $query = $this->attention->query();

        if (Auth::user()->office_id) {
            $query->where('attentions.user_id', Auth::user()->id);
        }

        $query->today();

        $query->select(
            'attentions.id',
            'attentions.report_number',
            'attentions.person_id',
            'attentions.person_type',
            'type_attentions.name as type_attention_name',
            'attentions.created_at'
        )
            ->join('type_attentions', 'type_attentions.id', '=', 'attentions.type_attention_id')
            ->orderBy('attentions.created_at', 'desc');

        $attentions = $query->get()->map(function ($attention) {
            if ($attention->person_type == '001') {
                $attention->person_name = Student::select(DB::raw("CONCAT_WS(' ', name, paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                $attention->unit_name = Career::select('name')->where('code', Student::select('career_code')->where('id', $attention->person_id)->first()->career_code)->first()->name;
            } else if ($attention->person_type == '002') {
                $attention->person_name = Professor::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                $attention->unit_name = Career::select('name')->where('code', Professor::select('career_code')->where('id', $attention->person_id)->first()->career_code)->first()->name;
            } else if ($attention->person_type == '003') {
                $attention->person_name = Worker::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                $attention->unit_name = Office::select('name')->where('id', Worker::select('office_id')->where('id', $attention->person_id)->first()->office_id)->first()->name;
            } else if ($attention->person_type == '004') {
                $attention->person_name = External::select(DB::raw("CONCAT(name,' ',paternal_surname) as name"))->where('id', $attention->person_id)->first()->name;
                $attention->unit_name = 'Externo';
            }
            return $attention;
        });
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
            $request['user_id'] = Auth::user()->id;
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
