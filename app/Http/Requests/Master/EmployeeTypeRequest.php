<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('employee_type') ? $this->route('employee_type')->id : null;
        return ['name' => 'required|string|max:255|unique:employee_types,name,' . $id];
    }
}
