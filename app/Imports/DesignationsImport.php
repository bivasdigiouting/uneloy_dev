<?php

namespace App\Imports;

use App\Models\Designation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DesignationsImport implements ToModel, WithCustomCsvSettings, WithHeadingRow, WithValidation
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Designation([
            'designation_name' => $row['designation_name'],
            'is_active' => $row['is_active'] ?? 1,
        ]);
    }

    public function rules(): array
    {
        return [
            'designation_name' => 'required|string|max:255|unique:designations,designation_name',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'designation_name.required' => 'The designation name field is required.',
            'designation_name.string' => 'The designation name must be a string.',
            'designation_name.max' => 'The designation name may not be greater than 255 characters.',
            'designation_name.unique' => 'The designation name has already been taken.',
            'is_active.boolean' => 'The is active field must be true or false.',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
        ];
    }
}
