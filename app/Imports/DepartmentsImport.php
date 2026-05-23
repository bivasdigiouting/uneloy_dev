<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['department_name'])) {
            return null;
        }

        return new Department([
            'department_name' => $row['department_name'],
            'remarks' => $row['remarks'] ?? null,
            'is_active' => isset($row['status']) ?
                (strtolower($row['status']) === 'active' || $row['status'] === '1' || $row['status'] === 1) : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'department_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'department_name'),
            ],
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'department_name.required' => 'Department name is required.',
            'department_name.unique' => 'Department name already exists.',
        ];
    }
}
