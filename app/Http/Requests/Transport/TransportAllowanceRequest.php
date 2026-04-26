<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransportAllowanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => [
                'required', 
                'exists:employees,id',
                Rule::unique('transport_allowances')
                    ->where(fn($q) => $q->where('month', $this->month)->where('year', $this->year))
                    ->ignore($this->route('transportAllowance'))
            ],
            'setting_id' => ['required', 'exists:transport_settings,id'],
            'km' => ['required', 'integer', 'min:0'],
            'working_days' => ['required', 'integer', 'min:0'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.unique' => 'Pegawai sudah memiliki data tunjangan untuk periode ini.',
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id' => 'Pegawai',
            'setting_id' => 'Tarif',
            'km' => 'Jarak (KM)',
            'working_days' => 'Hari Kerja',
            'month' => 'Bulan',
            'year' => 'Tahun',
        ];
    }
}
