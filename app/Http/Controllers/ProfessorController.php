<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Office;
use App\Models\Professor;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{

    protected $professor;

    public function __construct()
    {
        $this->professor = new Professor();
    }

    public function items(Request $request)
    {

        $query = $this->professor->query();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('paternal_surname', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('maternal_surname', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('document_number', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('code', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filtros dinámicos
        if ($request->has('filters') && is_array($request->filters)) {
            foreach ($request->filters as $filter => $value) {
                // Aquí puedes agregar validaciones adicionales según sea necesario
                if (!is_null($value)) {
                    $query->where(function ($q) use ($filter, $value) {
                        $q->where($filter, $value);
                    });
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
        $professors = Professor::latest()->get();
        return response()->json($professors);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:100',
                'paternal_surname' => 'nullable|max:80|required_without:maternal_surname',
                'maternal_surname' => 'nullable|max:80|required_without:paternal_surname',
                'document_type' => 'required|max:3',
                'document_number' => 'required|max:20|unique:professors',
                'birthdate' => 'nullable|date',
                'phone_number' => 'nullable|digits:9',
                'career_code' => 'nullable|max:3',
                'code' => 'nullable|max:20',
                'email' => 'nullable|email|max:255',
                'gender' => 'nullable|max:1',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'paternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'paternal_surname.max' => 'El apellido paterno no debe exceder los 80 caracteres',
                'maternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'maternal_surname.max' => 'El apellido materno no debe exceder los 80 caracteres',
                'document_type.required' => 'El tipo de documento es obligatorio',
                'document_number.required' => 'El número de documento es obligatorio',
                'document_number.unique' => 'El número de documento ya está en uso',
                'status.required' => 'El estado es obligatorio'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message' =>  "Error en la validación", 'errors' => $validator->errors()], 422);
        }

        try {
            Professor::create($request->except('id'));
            return response()->json([
                'message' => 'Profesor creado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|max:100',
                'paternal_surname' => 'nullable|max:80|required_without:maternal_surname',
                'maternal_surname' => 'nullable|max:80|required_without:paternal_surname',
                'document_type' => 'required|max:3',
                'document_number' => 'required|max:20',
                'birthdate' => 'nullable|date',
                'phone_number' => 'nullable|digits:9',
                'career_code' => 'nullable|max:3',
                'code' => 'nullable|max:20',
                'email' => 'nullable|email|max:255',
                'gender' => 'nullable|max:1',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'paternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'paternal_surname.max' => 'El apellido paterno no debe exceder los 80 caracteres',
                'maternal_surname.required_without' => 'El apellido paterno o materno es obligatorio',
                'maternal_surname.max' => 'El apellido materno no debe exceder los 80 caracteres',
                'document_type.required' => 'El tipo de documento es obligatorio',
                'document_number.required' => 'El número de documento es obligatorio',
                'status.required' => 'El estado es obligatorio'
            ]
        );

        try {
            $professor = Professor::find($id);
            $professor->update($request->except('id'));
            return response()->json([
                'message' => 'Profesor actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function search($term)
    {
        $professors = Professor::where('document_number', 'like', '%' . $term . '%')
            ->orWhereRaw("CONCAT_WS(' ', name, paternal_surname, maternal_surname) like '%$term%'")
            ->limit(10)
            ->get();
        return response()->json($professors);
    }

    public function getByDocument($document)
    {
        $professor = Professor::where('document_number', $document)->first();
        return response()->json($professor);
    }
}
