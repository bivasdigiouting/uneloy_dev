<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RechargeOperatorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recharge_service_id' => ['required', 'integer', 'exists:recharge_services,id'],
            'operator_name' => ['required', 'string', 'max:255'],
            'operator_code' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:recharge_operators,operator_code'],
            'operator_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
