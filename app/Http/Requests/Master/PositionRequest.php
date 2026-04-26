<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('position') ? $this->route('position')->id : null;
        return ['name' => 'required|string|max:255|unique:positions,name,' . $id];
    }
}
