<?php

namespace App\Services\Master;

use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentService
{
    public function getAllPaginated($search = null, $perPage = 10)
    {
        return Department::when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data)
    {
        return Department::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function update(Department $department, array $data)
    {
        return $department->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function delete(Department $department)
    {
        return $department->delete();
    }
}
