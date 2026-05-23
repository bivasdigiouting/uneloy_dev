<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RechargeServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('recharge_service');

        return [
            'service_name' => ['required', 'string', 'max:255'],
            'service_code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('recharge_services', 'service_code')->ignore($id),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
