<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage_companies',
            'manage_users',
            'manage_vacancies',
            'approve_leave',
            'view_attendance',
            'checkin_checkout',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }

        $roles = [
            'admin' => $permissions,
            'company-admin' => [
                'manage_users',
                'manage_vacancies',
                'approve_leave',
                'view_attendance',
            ],
            'hr' => [
                'manage_users',
                'approve_leave',
                'view_attendance',
            ],
            'employee' => [
                'checkin_checkout',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'sanctum',
            ]);

            $role->syncPermissions($rolePermissions);
        }
    }
}
