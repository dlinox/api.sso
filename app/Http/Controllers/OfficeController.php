<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfficeController extends Controller
{
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
}
