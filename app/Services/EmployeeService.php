<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    public function getAllPaginated($filters = [], $perPage = 10)
    {
        return Employee::with(['position', 'department', 'type'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('nip', 'LIKE', "%{$search}%")
                        ->orWhereHas('position', fn($pq) => $pq->where('name', 'LIKE', "%{$search}%"));
                });
            })
            ->when($filters['positions'] ?? null, function ($query, $positions) {
                $positions = array_filter((array) $positions);
                if (!empty($positions)) {
                    $query->whereIn('position_id', $positions);
                }
            })
            ->when($filters['tenure_operator'] ?? null, function ($query, $operator) use ($filters) {
                $value = $filters['tenure_value'] ?? 0;
                if (in_array($operator, ['>', '=', '<'])) {
                    $date = now()->subYears($value)->format('Y-m-d');
                    if ($operator === '>') {
                        $query->where('join_date', '<', $date);
                    } elseif ($operator === '<') {
                        $query->where('join_date', '>', $date);
                    } else {
                        $query->whereYear('join_date', now()->year - $value);
                    }
                }
            })
            ->orderBy($filters['sort_by'] ?? 'name', $filters['sort_dir'] ?? 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['photo'])) {
                $data['photo'] = $data['photo']->store('employees/photos', 'public');
            }

            $employee = Employee::create($data);

            $employee->address()->create([
                'district' => $data['district'],
                'regency' => $data['regency'],
                'province' => $data['province'],
                'full_address' => $data['full_address'],
            ]);

            if (!empty($data['educations'])) {
                $employee->educations()->createMany($data['educations']);
            }

            return $employee;
        });
    }

    public function update(Employee $employee, array $data)
    {
        return DB::transaction(function () use ($employee, $data) {
            if (isset($data['photo'])) {
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $data['photo'] = $data['photo']->store('employees/photos', 'public');
            }

            $employee->update($data);

            // Update/Create Address
            $employee->address()->updateOrCreate([], [
                'district' => $data['district'],
                'regency' => $data['regency'],
                'province' => $data['province'],
                'full_address' => $data['full_address'],
            ]);

            // Update Educations (Simple sync by delete and recreate)
            if (isset($data['educations'])) {
                $employee->educations()->delete();
                if (!empty($data['educations'])) {
                    $employee->educations()->createMany($data['educations']);
                }
            }

            return $employee;
        });
    }

    public function delete(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        return $employee->delete();
    }

    public function bulkDelete(array $ids)
    {
        $employees = Employee::whereIn('id', $ids)->get();
        foreach ($employees as $employee) {
            $this->delete($employee);
        }
        return true;
    }

    public function bulkUpdateStatus(array $ids, bool $isActive)
    {
        return Employee::whereIn('id', $ids)->update(['is_active' => $isActive]);
    }
}
