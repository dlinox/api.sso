<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{

    public function index()
    {
        $professors = Worker::latest()->get();
        return response()->json($professors);
    }

    public function offices()
    {
        $professors = Office::select('id', 'name')->latest()->active()->get();
        return response()->json($professors);
    }

    public function store(Request $request)
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
            $professor = Worker::create($request->except('id'));
            return response()->json($professor);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
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
            $professor = Worker::find($id);
            $professor->update($request->except('id'));
            return response()->json($professor);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function search($term)
    {
        $professors = Worker::where('document_number', 'like', '%' . $term . '%')
            ->orWhereRaw("CONCAT_WS(' ', name, paternal_surname, maternal_surname) like '%$term%'")
            ->get();
        return response()->json($professors);
    }

    public function getByDocument($document)
    {
        $professor = Worker::where('document_number', $document)->first();
        return response()->json($professor);
    }
}
