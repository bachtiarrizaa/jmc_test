<?php

namespace App\Services\Master;

use App\Models\EmployeeType;
use Illuminate\Support\Str;

class EmployeeTypeService
{
    public function getAllPaginated($search = null, $perPage = 10)
    {
        return EmployeeType::when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data) {
        return EmployeeType::create(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);
    }

    public function update(EmployeeType $type, array $data) {
        return $type->update(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);
    }

    public function delete(EmployeeType $type) { return $type->delete(); }
}
