<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'nip' => [
                'required',
                'string',
                'min:8',
                'regex:/^[0-9]+$/',
                Rule::unique('employees', 'nip')->ignore($employeeId),
            ],
            'name' => 'required|string|regex:/^[a-zA-Z0-9\s\']+$/',
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'phone' => ['required', 'string', 'regex:/^\+[0-9]{10,20}$/'],
            'photo' => 'nullable|image|mimes:png,jpeg,jpg|max:2048',
            'gender' => 'required|in:pria,wanita',
            'marital_status' => 'required|in:kawin,tidak kawin',
            'children_count' => 'required|integer|min:0|max:99',
            'birthdate' => 'required|date|before:today',
            'birthplace' => 'required|string',
            'join_date' => 'required|date',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'employee_type_id' => 'required|exists:employee_types,id',
            'is_active' => 'boolean',

            'district' => 'required|string',
            'regency' => 'required|string',
            'province' => 'required|string',
            'full_address' => 'required|string',

            'educations' => 'nullable|array',
            'educations.*.level' => 'required|string',
            'educations.*.institution' => 'required|string',
            'educations.*.graduation_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
        ];
    }

    public function messages(): array
    {
        return [
            'nip.regex' => 'NIP hanya boleh berisi angka dan tidak boleh ada spasi.',
            'nip.min' => 'NIP minimal 8 karakter.',
            'name.regex' => 'Nama hanya boleh huruf, angka, tanda petik (\'), dan spasi.',
            'phone.regex' => 'Nomor HP harus format internasional (contoh: +6282218458888).',
        ];
    }
}
