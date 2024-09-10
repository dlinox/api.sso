<?php

namespace App\Http\Controllers;

use App\Models\TypeAttention;
use Illuminate\Http\Request;

class TypeAttentionController extends Controller
{

    protected $typeAttention;

    public function __construct()
    {
        $this->typeAttention = new TypeAttention();
    }

    public function items(Request $request)
    {

        $query = $this->typeAttention->query();

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

        $offices = $query->latest()->paginate($perPage);
        return response()->json($offices);
    }



    public function index()
    {
        $typeAttentions = TypeAttention::latest()->get();
        return response()->json($typeAttentions);
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

        // $request['id'] = (string) Str::uuid();
        try {
            $typeAttention = TypeAttention::create($request->only('id', 'name', 'status'));
            return response()->json([
                'message' => 'Tipo de atención creado con éxito',
                'typeAttention' => $typeAttention
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function  update(Request $request, $typeAttention)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);

        try {
            $typeAttention = TypeAttention::find($typeAttention);
            $typeAttention->update($request->only('name', 'status'));
            return response()->json([
                'message' => 'Tipo de atención actualizado con éxito',
                'typeAttention' => $typeAttention
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function options()
    {
        $typeAttentions = TypeAttention::select('id', 'name')->active()->get();
        return response()->json($typeAttentions);
    }

    public function forSelect()
    {
        $typeAttentions = TypeAttention::select('id as value', 'name as title')->active()->get();
        return response()->json($typeAttentions);
    }
}
