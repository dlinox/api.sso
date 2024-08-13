<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfficeController extends Controller
{
    protected $office;

    public function __construct()
    {
        $this->office = new Office();
    }

    public function items(Request $request)
    {

        $query = $this->office->query();

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

        $offices = $query->latest()->paginate($perPage);
        return response()->json($offices);
    }

    public function index()
    {

        $offices = Office::latest()->get();
        return response()->json($offices);
    }

    public function store(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'status.required' => 'El estado es obligatorio'
            ]
        );
        try {
            $office = Office::create($request->only('id', 'name', 'status'));
            return response()->json($office);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function  update(Request $request, $office)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);

        $office = Office::find($office);
        $office->update($request->only('name', 'status'));

        return response()->json($office);
    }

    public function options()
    {
        $offices = Office::select('id', 'name')->active()->get();
        return response()->json($offices);
    }

    public function forSelect()
    {
        $offices = Office::forSelect()->active()->get();
        return response()->json($offices);
    }
}
