<?php

namespace App\Services\Master;

use App\Models\Position;
use Illuminate\Support\Str;

class PositionService
{
    public function getAllPaginated($search = null, $perPage = 10)
    {
        return Position::when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data) {
        return Position::create(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);
    }

    public function update(Position $position, array $data) {
        return $position->update(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);
    }

    public function delete(Position $position) { return $position->delete(); }
}
