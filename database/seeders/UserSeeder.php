<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        //app/constants.php
        $permissions = config('constants.permissions');

        foreach ($permissions as $permission) {
            $newPermission = Permission::create([
                'name' => $permission['name'],
                'description' => $permission['description'],
                //api sanctum
                'guard_name' => 'web',
                'type' => $permission['type'],
            ]);

            foreach ($permission['actions'] as $action) {
                Permission::create([
                    'name' => $action['name'],
                    'description' => $action['description'],
                    //api sanctum
                    'guard_name' => 'web',
                    'type' => $action['type'],
                    'parent_id' => $newPermission->id,
                ]);
            }
        }

        $roleSuperAdmin = Role::create(['name' => 'super', 'is_super' => true]);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        $super = User::create([
            'name' => 'Super',
            'paternal_surname' => 'Admin',
            'maternal_surname' => 'Admin',
            'document_type' => '000',
            'document_number' => '00000000',
            'email' => 'serviciosocial@unap.edu.pe',
            'password' => 'super@test.com',
            'office_id' => null,
            'is_editable' => false,
            'status' => true,
        ]);

        $super->assignRole($roleSuperAdmin);
    }
}
