<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RechargeOperatorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('recharge_operator');

        return [
            'recharge_service_id' => ['required', 'integer', 'exists:recharge_services,id'],
            'operator_name' => ['required', 'string', 'max:255'],
            'operator_code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('recharge_operators', 'operator_code')->ignore($id),
            ],
            'operator_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
