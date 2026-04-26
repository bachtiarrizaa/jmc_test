<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Services\Master\RoleService;
use App\Http\Requests\Master\RoleRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $roles = $this->roleService->getAllPaginated($request->get('search'), $request->get('per_page', 10));
        return view('master.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->roleService->getPermissionsGrouped();
        return view('master.roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        $this->roleService->create($request->validated());
        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        if ($role->name === 'Superadmin') {
            return redirect()->route('roles.index')->with('error', 'Role Superadmin tidak dapat diedit.');
        }

        $permissions = $this->roleService->getPermissionsGrouped();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('master.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $this->roleService->update($role, $request->validated());
        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        try {
            $this->roleService->delete($role);
            return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', $e->getMessage());
        }
    }
}
