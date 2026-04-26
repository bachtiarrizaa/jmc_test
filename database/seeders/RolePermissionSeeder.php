<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard' => [
                'path' => '/dashboard',
                'actions' => ['dashboard.index']
            ],
            'departments' => [
                'path' => '/departments',
                'actions' => ['departments.index', 'departments.create', 'departments.edit', 'departments.delete']
            ],
            'positions' => [
                'path' => '/positions',
                'actions' => ['positions.index', 'positions.create', 'positions.edit', 'positions.delete']
            ],
            'employee_types' => [
                'path' => '/employee-types',
                'actions' => ['employee_types.index', 'employee_types.create', 'employee_types.edit', 'employee_types.delete']
            ],
            'roles' => [
                'path' => '/roles',
                'actions' => ['roles.index', 'roles.create', 'roles.edit', 'roles.delete']
            ],
            'users' => [
                'path' => '/users',
                'actions' => ['users.index', 'users.create', 'users.edit', 'users.delete']
            ],
            'employees' => [
                'path' => '/employees',
                'actions' => ['employees.index', 'employees.create', 'employees.edit', 'employees.delete', 'employees.export']
            ],
            'activity_logs' => [
                'path' => '/logs',
                'actions' => ['activity_logs.index']
            ],
            'transport_settings' => [
                'path' => '/transport/settings',
                'actions' => ['transport_settings.index', 'transport_settings.create', 'transport_settings.edit', 'transport_settings.delete']
            ],
            'transport_allowances' => [
                'path' => '/transport/allowances',
                'actions' => ['transport_allowances.index', 'transport_allowances.create', 'transport_allowances.edit', 'transport_allowances.delete']
            ],
        ];

        foreach ($permissions as $module => $data) {
            $parent = Permission::updateOrCreate(
                ['name' => $module],
                ['path' => $data['path'], 'guard_name' => 'web']
            );

            foreach ($data['actions'] as $action) {
                Permission::updateOrCreate(
                    ['name' => $action],
                    ['parent_id' => $parent->id, 'guard_name' => 'web']
                );
            }
        }

        $superadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $managerHrd = Role::firstOrCreate(['name' => 'Manager HRD']);
        $adminHrd = Role::firstOrCreate(['name' => 'Admin HRD']);
        $karyawan = Role::firstOrCreate(['name' => 'Karyawan']);

        $superadmin->syncPermissions([
            'dashboard.index',
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'activity_logs.index',
        ]);

        $managerHrd->syncPermissions([
            'dashboard.index',
            'users.index',
            'users.edit',
            'employees.index',
            'transport_allowances.index',
            'departments.index',
            'positions.index',
            'employee_types.index',
        ]);

        $adminHrd->syncPermissions([
            'dashboard.index',
            'users.index',
            'users.edit',
            'employees.index',
            'employees.create',
            'employees.edit',
            'employees.delete',
            'employees.export',
            'transport_allowances.index',
            'transport_settings.index',
            'transport_settings.create',
            'transport_settings.edit',
            'transport_settings.delete',
            'departments.index',
            'departments.create',
            'departments.edit',
            'departments.delete',
            'positions.index',
            'positions.create',
            'positions.edit',
            'positions.delete',
            'employee_types.index',
            'employee_types.create',
            'employee_types.edit',
            'employee_types.delete',
        ]);

        $karyawan->syncPermissions(['dashboard.index']);
    }
}
