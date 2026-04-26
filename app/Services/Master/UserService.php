<?php

namespace App\Services\Master;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAllPaginated($search = null, $perPage = 10)
    {
        return User::with(['roles', 'employee'])
            ->when($search, function ($query, $search) {
                $query->where('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('employee', fn($q) => $q->where('name', 'LIKE', "%{$search}%"));
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'employee_id' => $data['employee_id'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });
    }

    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $updateData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'employee_id' => $data['employee_id'] ?? null,
                'is_active' => $data['is_active'] ?? $user->is_active,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            return $user;
        });
    }

    public function delete(User $user)
    {
        if (auth()->id() === $user->id) {
            throw new \Exception("Anda tidak dapat menghapus data Anda sendiri.");
        }

        return $user->delete();
    }
}
