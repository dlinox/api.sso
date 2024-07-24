<?php

namespace App\Http\Controllers;

use App\Models\TypeAttention;
use Illuminate\Http\Request;

class TypeAttentionController extends Controller
{

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
            return response()->json($typeAttention);
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

        $typeAttention = TypeAttention::find($typeAttention);
        $typeAttention->update($request->only('name', 'status'));

        return response()->json($typeAttention);
    }

    public function options()
    {
        $typeAttentions = TypeAttention::select('id', 'name')->active()->get();
        return response()->json($typeAttentions);
    }

    
}
