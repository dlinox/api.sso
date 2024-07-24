<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $professors = Role::latest()->select('roles.id', 'roles.name', DB::raw('GROUP_CONCAT(role_has_permissions.permission_id) as permissions'))
            ->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
            ->where('role_has_permissions.permission_id', '!=', 1)
            ->groupBy('roles.id')
            ->get()->map(function ($role) {
                $role->permissions = explode(',',  $role->permissions);
                $role->permissions = array_map('intval', $role->permissions);
                return $role;
            });

        return response()->json($professors);
    }


    public function permissions()
    {
        $permissions = Permission::select('id', 'name', 'description', 'type')->where('id', '!=', 1)->get();

        return response()->json($permissions);
    }

    public function assignPermissions(Request $request, $role)
    {
        $role = Role::find($role);
        $role->syncPermissions($request->permissions);
        return response()->json($role);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:100|unique:roles',
            ],
            [
                'name.required' => 'El nombre es obligatorio',
            ]
        );

        $request->merge(['guard_name' => 'web']);

        try {
            $professor = Role::create($request->except('id'));

            return response()->json($professor);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $role)
    {
        $request->validate(
            [
                'name' => 'required|max:100|unique:roles,name,' . $role,
            ],
            [
                'name.required' => 'El nombre es obligatorio',
            ]
        );

        try {
            $role = Role::find($role);
            $role->update($request->only('name'));
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
