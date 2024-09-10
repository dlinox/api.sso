<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    protected $worker;

    public function __construct()
    {
        $this->worker = new Worker();
    }

    public function items(Request $request)
    {

        $query = $this->worker->query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtros dinámicos
        if ($request->has('filters') && is_array($request->filters)) {
            foreach ($request->filters as $filter => $value) {
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
                'email' => 'required|email|max:255|unique:workers,email',
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
                'status.required' => 'El estado es obligatorio',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.unique' => 'El correo electrónico ya se encuentra registrado'
            ]
        );

        try {
            Worker::create($request->except('id'));
            return response()->json([
                'message' => 'Item registrado correctamente',
            ]);
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
                'email' => 'required|email|max:255|unique:workers,email,' . $id,
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
                'status.required' => 'El estado es obligatorio',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.unique' => 'El correo electrónico ya se encuentra registrado'
            ]
        );

        try {
            $item = Worker::find($id);
            $item->update($request->except('id'));
            return response()->json([
                'message' => 'Item actualizado correctamente',
            ]);
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
