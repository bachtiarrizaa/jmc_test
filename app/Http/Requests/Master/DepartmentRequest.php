<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('department') ? $this->route('department')->id : null;
        
        return [
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama departemen sudah terdaftar.',
            'name.required' => 'Nama departemen wajib diisi.',
        ];
    }
}
