<?php

namespace App\Imports;

use App\Models\Designation;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StaffImport implements ToModel, WithCustomCsvSettings, WithHeadingRow, WithValidation
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['staff_name']) || empty($row['email_id'])) {
            return null;
        }

        // Find designation by name
        $designation = Designation::where('designation_name', $row['designation'])->first();

        return new Staff([
            'staff_name' => $row['staff_name'],
            'email_id' => $row['email_id'],
            'mobile_no' => $row['mobile_no'] ?? null,
            'designation_id' => $designation ? $designation->id : null,
            'date_of_joining' => $row['date_of_joining'] ? date('Y-m-d', strtotime($row['date_of_joining'])) : null,
            'date_of_birth' => $row['date_of_birth'] ? date('Y-m-d', strtotime($row['date_of_birth'])) : null,
            'gender' => $row['gender'] ?? 'Male',
            'address_1' => $row['address_1'] ?? null,
            'address_2' => $row['address_2'] ?? null,
            'state' => $row['state'] ?? null,
            'district' => $row['district'] ?? null,
            'city' => $row['city'] ?? null,
            'pincode' => $row['pincode'] ?? null,
            'location' => $row['location'] ?? null,
            'ifsc_code' => $row['ifsc_code'] ?? null,
            'bank_name' => $row['bank_name'] ?? null,
            'branch_name' => $row['branch_name'] ?? null,
            'account_no' => $row['account_no'] ?? null,
            'pan_no' => $row['pan_no'] ?? null,
            'aadhar_no' => $row['aadhar_no'] ?? null,
            'salary' => $row['salary'] ?? 0,
            'user_id' => $row['user_id'] ?? null,
            'password' => isset($row['password']) ? Hash::make($row['password']) : Hash::make('password123'),
            'is_active' => isset($row['status']) ?
                (strtolower($row['status']) === 'active' || $row['status'] === '1' || $row['status'] === 1) : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'staff_name' => 'required|string|max:255',
            'email_id' => [
                'required',
                'email',
                'max:255',
                Rule::unique('staff', 'email_id'),
            ],
            'mobile_no' => 'nullable|string|max:15',
            'designation' => 'nullable|string|exists:designations,designation_name',
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address_1' => 'nullable|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'location' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:20',
            'pan_no' => 'nullable|string|max:10',
            'aadhar_no' => 'nullable|string|max:12',
            'salary' => 'nullable|numeric|min:0',
            'user_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('staff', 'user_id'),
            ],
            'password' => 'nullable|string|min:6',
            'status' => 'nullable|string',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'staff_name.required' => 'The staff name field is required.',
            'staff_name.string' => 'The staff name must be a string.',
            'staff_name.max' => 'The staff name may not be greater than 255 characters.',
            'email_id.required' => 'The email ID field is required.',
            'email_id.email' => 'The email ID must be a valid email address.',
            'email_id.unique' => 'The email ID has already been taken.',
            'mobile_no.max' => 'The mobile number may not be greater than 15 characters.',
            'designation.exists' => 'The selected designation does not exist.',
            'date_of_joining.date' => 'The date of joining must be a valid date.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'gender.in' => 'The gender must be Male, Female, or Other.',
            'pincode.max' => 'The pincode may not be greater than 10 characters.',
            'ifsc_code.max' => 'The IFSC code may not be greater than 11 characters.',
            'account_no.max' => 'The account number may not be greater than 20 characters.',
            'pan_no.max' => 'The PAN number may not be greater than 10 characters.',
            'aadhar_no.max' => 'The Aadhar number may not be greater than 12 characters.',
            'salary.numeric' => 'The salary must be a number.',
            'salary.min' => 'The salary must be at least 0.',
            'user_id.unique' => 'The user ID has already been taken.',
            'password.min' => 'The password must be at least 6 characters.',
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
