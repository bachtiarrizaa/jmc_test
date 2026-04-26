<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class TransportSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'base_fare' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'base_fare' => 'Tarif Dasar',
            'effective_date' => 'Tanggal Efektif',
        ];
    }
}
