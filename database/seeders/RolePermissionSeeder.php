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

        $superadmin->givePermissionTo(Permission::all());

        $managerHrd->givePermissionTo(['dashboard.index', 'activity_logs.index']);

        $adminHrd->givePermissionTo([
            'dashboard.index',
            'departments.index',
            'departments.create',
            'departments.edit',
            'positions.index',
            'positions.create',
            'positions.edit',
            'employee_types.index',
            'employee_types.create',
            'employee_types.edit',
            'employees.index',
            'employees.create',
            'employees.edit',
            'employees.delete',
            'users.index',
            'users.edit'
        ]);
    }
}
