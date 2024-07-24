<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentionController extends Controller
{

    public function index()
    {
        $attentions = Attention::latest()->get();
        return response()->json($attentions);
    }

    public function last()
    {
        $attentions = Attention::select(
                'attentions.id',
                'attentions.report_number',
                'attentions.description',
                DB::raw('CONCAT_WS(" ", students.name, students.paternal_surname, students.maternal_surname) as student_name'),
                'type_attentions.name as type_attention_name'
            )
            ->join('students', 'students.id', '=', 'attentions.student_id')
            ->join('type_attentions', 'type_attentions.id', '=', 'attentions.type_attention_id')
            ->orderBy('attentions.created_at', 'desc')
            ->limit(10)->get();
        return response()->json($attentions);
    }

    public function store(Request $request, $type)
    {

        $request->validate(
            [
                'report_number' => 'required|max:255',
                'description' => 'required',
                'student_id' => 'required|integer',
                'type_attention_id' => 'required|integer',
            ],
            [
                'report_number.required' => 'El número de reporte es obligatorio',
                'description.required' => 'La descripción es obligatoria',
                'student_id.required' => 'El estudiante es obligatorio',
                'type_attention_id.required' => 'El tipo de atención es obligatorio',
            ]
        );
        try {
            $request['type_person'] = $type;
            $request['user_id'] = auth()->user()->id;
            $request['derivations'] = implode(',', $request->derivate_to);
            $attention = Attention::create($request->only('report_number', 'description', 'derivations', 'student_id', 'type_attention_id', 'user_id', 'type_person'));
            return response()->json($attention);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
