<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use App\Services\Master\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllPaginated($request->get('search'), $request->get('per_page', 10));
        return view('master.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $employees = Employee::orderBy('name')->get();
        return view('master.users.create', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:6|unique:users,username|regex:/^[a-z0-9]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'role' => 'required|exists:roles,name',
            'employee_id' => 'required|exists:employees,id',
            'is_active' => 'boolean'
        ]);

        $this->userService->create($validated);
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $employees = Employee::orderBy('name')->get();
        return view('master.users.edit', compact('user', 'roles', 'employees'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:6', 'regex:/^[a-z0-9]+$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'role' => 'required|exists:roles,name',
            'employee_id' => 'required|exists:employees,id',
            'is_active' => 'boolean'
        ]);

        $this->userService->update($user, $validated);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function checkUsername(Request $request)
    {
        $username = $request->get('username');
        $excludeId = $request->get('exclude_id');

        $exists = User::where('username', $username)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->delete($user);
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }
}
