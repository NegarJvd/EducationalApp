<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $SuperAdmin_permissions_extra = [
            'role-list', 'role-create', 'role-edit', 'role-delete',
        ];

        $Manager_permissions = [
            'admin-list', 'admin-create', 'admin-edit', 'admin-delete', 'change_admin_role', 'search_in_admins_list', 'change-admin-status',
        ];
        $Therapist_permissions = [

        ];

        $Manager_permissions_id = [];
        $Therapist_permissions_id = [];

        foreach ($SuperAdmin_permissions_extra as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        foreach ($Manager_permissions as $permission) {
            $Manager_permissions_id[] = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        foreach ($Therapist_permissions as $permission) {
            $Therapist_permissions_id[] = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $Manager = Role::firstOrCreate(['name'=>'Manager']);
        $Therapist = Role::firstOrCreate(['name'=>'Therapist']);

        $Manager->syncPermissions($Manager_permissions_id);
        $Therapist->syncPermissions($Therapist_permissions_id);
    }
}
