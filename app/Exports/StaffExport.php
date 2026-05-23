<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Staff::with('designation')->orderBy('staff_name')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Staff Name',
            'Email ID',
            'Mobile No',
            'Designation',
            'Date of Joining',
            'Date of Birth',
            'Gender',
            'Address 1',
            'Address 2',
            'State',
            'District',
            'City',
            'Pincode',
            'Location',
            'IFSC Code',
            'Bank Name',
            'Branch Name',
            'Account No',
            'PAN No',
            'Aadhar No',
            'Salary',
            'User ID',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param  Staff  $staff
     */
    public function map($staff): array
    {
        return [
            $staff->id,
            $staff->staff_name,
            $staff->email_id,
            $staff->mobile_no,
            $staff->designation->designation_name ?? 'N/A',
            $staff->date_of_joining ? date('Y-m-d', strtotime($staff->date_of_joining)) : '',
            $staff->date_of_birth ? date('Y-m-d', strtotime($staff->date_of_birth)) : '',
            $staff->gender,
            $staff->address_1,
            $staff->address_2,
            $staff->state,
            $staff->district,
            $staff->city,
            $staff->pincode,
            $staff->location,
            $staff->ifsc_code,
            $staff->bank_name,
            $staff->branch_name,
            $staff->account_no,
            $staff->pan_no,
            $staff->aadhar_no,
            $staff->salary,
            $staff->user_id,
            $staff->is_active ? 'Active' : 'Inactive',
            $staff->created_at->format('Y-m-d H:i:s'),
            $staff->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
