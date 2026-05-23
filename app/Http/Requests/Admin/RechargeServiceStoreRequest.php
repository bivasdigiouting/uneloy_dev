<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RechargeServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_name' => ['required', 'string', 'max:255'],
            'service_code' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:recharge_services,service_code'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
