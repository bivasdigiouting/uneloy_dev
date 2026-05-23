<?php

namespace App\Exports;

use App\Models\Designation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DesignationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Designation::orderBy('designation_name')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Designation Name',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param  Designation  $designation
     */
    public function map($designation): array
    {
        return [
            $designation->id,
            $designation->designation_name,
            $designation->is_active ? 'Active' : 'Inactive',
            $designation->created_at->format('Y-m-d H:i:s'),
            $designation->updated_at->format('Y-m-d H:i:s'),
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
