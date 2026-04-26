<?php

namespace App\Services\Master;

use Spatie\Permission\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function getAllPaginated($search = null, $perPage = 10)
    {
        return Role::with('permissions')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            return $role;
        });
    }

    public function update(Role $role, array $data)
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update(['name' => $data['name']]);
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            return $role;
        });
    }

    public function delete(Role $role)
    {
        if ($role->name === 'Superadmin') {
            throw new \Exception('Role Superadmin tidak dapat dihapus.');
        }
        return $role->delete();
    }

    public function getPermissionsGrouped()
    {
        return Permission::whereNull('parent_id')
            ->with('children')
            ->get();
    }
}
