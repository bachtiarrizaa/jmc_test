<?php

namespace App\Services\Transport;

use App\Models\TransportAllowance;
use App\Models\Employee;
use App\Models\TransportSetting;

class TransportAllowanceService
{
    public function getAllPaginated($search = null, $month = null, $year = null, $perPage = 10)
    {
        $user = auth()->user();
        $query = TransportAllowance::with(['employee', 'setting']);

        // Sesuai RBAC: Manager HRD & Admin HRD hanya RO (data yang dia input)
        // Superadmin bisa lihat semua
        if ($user && !$user->hasRole('Superadmin')) {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereNull('created_by');
            });
        }

        return $query
            ->when($search, function($q) use ($search) {
                $q->whereHas('employee', function($eq) use ($search) {
                    $eq->where('name', 'LIKE', "%{$search}%")
                       ->orWhere('nip', 'LIKE', "%{$search}%");
                });
            })
            ->when($month, fn($q) => $q->where('month', $month))
            ->when($year, fn($q) => $q->where('year', $year))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data)
    {
        $employee = Employee::findOrFail($data['employee_id']);
        $setting = TransportSetting::findOrFail($data['setting_id']);
        
        $amount = TransportAllowance::calculateAmount(
            $setting->base_fare,
            $data['km'],
            $data['working_days'],
            $employee->isPermanent()
        );

        return TransportAllowance::create([
            'employee_id' => $data['employee_id'],
            'setting_id' => $data['setting_id'],
            'km' => $data['km'],
            'working_days' => $data['working_days'],
            'month' => $data['month'],
            'year' => $data['year'],
            'amount' => $amount,
        ]);
    }

    public function update(TransportAllowance $allowance, array $data)
    {
        $employee = $allowance->employee;
        $setting = TransportSetting::findOrFail($data['setting_id']);

        $amount = TransportAllowance::calculateAmount(
            $setting->base_fare,
            $data['km'],
            $data['working_days'],
            $employee->isPermanent()
        );

        return $allowance->update([
            'setting_id' => $data['setting_id'],
            'km' => $data['km'],
            'working_days' => $data['working_days'],
            'month' => $data['month'],
            'year' => $data['year'],
            'amount' => $amount,
        ]);
    }

    public function delete(TransportAllowance $allowance)
    {
        return $allowance->delete();
    }
}
