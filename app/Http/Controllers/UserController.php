<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function items(Request $request)
    {

        $query = $this->user->query();

        if ($request->has('search')) {

            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('paternal_surname', 'like', '%' . $search . '%')
                    ->orWhere('maternal_surname', 'like', '%' . $search . '%')
                    ->orWhere('document_number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
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

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'paternal_surname' =>  'required|max:255',
                'maternal_surname' => 'required|max:255',
                // 'document_type' => 'required|max:3',
                'document_number' =>  'required|max:20',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',
                'office_id' => 'nullable|exists:offices,id',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'paternal_surname.required' => 'El apellido paterno es obligatorio',
                'maternal_surname.required' => 'El apellido materno es obligatorio',
                // 'document_type.required' => 'El tipo de documento es obligatorio',
                'document_number.required' => 'El número de documento es obligatorio',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.unique' => 'El correo electrónico ya está en uso',
                'role_id.required' => 'El rol es obligatorio',
                'password.required' => 'La contraseña es obligatoria',
                'status.required' => 'El estado es obligatorio'
            ]
        );
        try {
            DB::beginTransaction();
            $user = User::create($request->only('name', 'paternal_surname', 'maternal_surname',  'document_number', 'email', 'password', 'office_id', 'status'));
            $user->assignRole($request->role_id);
            DB::commit();
            return response()->json(['message' => 'Usuario creado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $user)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'paternal_surname' =>  'required|max:255',
                'maternal_surname' => 'required|max:255',
                'document_type' => 'required|max:3',
                'document_number' =>  'required|max:20',
                'email' => 'required|email|max:255|unique:users,email,' . $user,
                'office_id' => 'nullable|exists:offices,id',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|boolean'
            ],
            [
                'name.required' => 'El nombre es obligatorio',
                'paternal_surname.required' => 'El apellido paterno es obligatorio',
                'maternal_surname.required' => 'El apellido materno es obligatorio',
                'document_type.required' => 'El tipo de documento es obligatorio',
                'document_number.required' => 'El número de documento es obligatorio',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.unique' => 'El correo electrónico ya está en uso',
                'role_id.required' => 'El rol es obligatorio',
                'status.required' => 'El estado es obligatorio'
            ]
        );
        try {
            DB::beginTransaction();
            $user = User::find($user);
            $user->update($request->only('name', 'paternal_surname', 'maternal_surname', 'document_type', 'document_number', 'email', 'office_id', 'status'));
            $user->syncRoles($request->role_id);
            DB::commit();
            return response()->json(['message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json($e->getMessage());
        }
    }

    public function forSelect()
    {
        $users = DB::select("
            SELECT id as value, concat_ws(' ', name, paternal_surname,maternal_surname ) as title 
            FROM users;");
        return response()->json($users);
    }
}
