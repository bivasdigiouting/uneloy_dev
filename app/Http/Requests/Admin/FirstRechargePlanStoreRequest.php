<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FirstRechargePlanStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_name' => ['required', 'string', 'max:255', 'unique:first_recharge_plans,plan_name'],
            'plan_value' => ['required', 'numeric', 'min:0'],
            'bonus_value' => ['required', 'numeric', 'min:0'],
            'benefit_amount' => ['required', 'numeric', 'min:0'],
            'benefit_duration_years' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
