<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{


    protected $role;

    public function __construct()
    {
        $this->role = new  \Spatie\Permission\Models\Role();
    }

    public function items(Request $request)
    {

        $query = $this->role->query();

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

        $query->select('roles.id', 'roles.name', DB::raw('GROUP_CONCAT(role_has_permissions.permission_id) as permissions'))
            ->leftJoin('role_has_permissions', function ($join) {
                $join->on('roles.id', '=', 'role_has_permissions.role_id')
                    ->where("role_has_permissions.permission_id", '!=', 1);
            })
            ->groupBy('roles.id');


        $offices = $query->latest()->paginate($perPage);

        return response()->json($offices);
    }

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
        $permissions = Permission::select('id as value', DB::raw("concat_ws(' | ', if(type = '001', 'MENU', 'ACCIÓN'), description  ) as title"))->where('id', '!=', 1)->get();

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

            //asignar permisos
            $professor->syncPermissions($request->permissions);

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
            $role->syncPermissions($request->permissions);
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    //forSelect
    public function forSelect()
    {
        $roles = Role::select('id as value', 'name as title')->get();
        return response()->json($roles);
    }
}
