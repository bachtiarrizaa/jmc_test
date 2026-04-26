<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jabatan',
            'Departemen',
            'Tipe',
            'Email',
            'Telepon',
            'Tanggal Masuk',
            'Masa Kerja (Tahun)',
            'Status'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->nip,
            $employee->name,
            $employee->position?->name,
            $employee->department?->name,
            $employee->type?->name,
            $employee->email,
            $employee->phone,
            $employee->join_date->format('d/m/Y'),
            $employee->tenure,
            $employee->is_active ? 'Aktif' : 'Nonaktif'
        ];
    }
}
