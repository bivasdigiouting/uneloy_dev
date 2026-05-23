<?php

namespace App\Http\Requests\Admin;

use App\Models\FirstRechargePlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FirstRechargePlanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeParam = $this->route('first_recharge_plan');
        $id = $routeParam instanceof FirstRechargePlan ? (int) $routeParam->id : (int) $routeParam;

        return [
            'plan_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('first_recharge_plans', 'plan_name')->ignore($id),
            ],
            'plan_value' => ['required', 'numeric', 'min:0'],
            'bonus_value' => ['required', 'numeric', 'min:0'],
            'benefit_amount' => ['required', 'numeric', 'min:0'],
            'benefit_duration_years' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
