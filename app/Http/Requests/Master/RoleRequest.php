<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('role') ? $this->route('role') : null;
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id'
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => 'Pilih minimal satu permission untuk role ini.',
        ];
    }
}
