<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DepartmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Department::orderBy('department_name')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Department Name',
            'Remarks',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param  Department  $department
     */
    public function map($department): array
    {
        return [
            $department->id,
            $department->department_name,
            $department->remarks,
            $department->is_active ? 'Active' : 'Inactive',
            $department->created_at->format('Y-m-d H:i:s'),
            $department->updated_at->format('Y-m-d H:i:s'),
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
