<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CommissionTdsChargeReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $levelDepartmentNames = [
            'State e-Card Seva',
            'District e-Card Seva',
            'Block - e-Card Seva',
            'G P M e-Card Seva',
            'e-Card Seva',
        ];
        $legacyLevelDepartmentNames = [
            'State Level',
            'District Level',
            'Block Level',
            'Panchayat Level',
            'Village Level',
        ];

        $level = trim((string) ($this->filters['level'] ?? ''));
        $fromDate = trim((string) ($this->filters['from_date'] ?? ''));
        $toDate = trim((string) ($this->filters['to_date'] ?? ''));

        $qb = DB::table('departments as d')
            ->leftJoin('department_commissions as dc', 'dc.department_id', '=', 'd.id')
            ->whereIn('d.department_name', array_merge($levelDepartmentNames, $legacyLevelDepartmentNames))
            ->select([
                'd.department_name',
                DB::raw('COALESCE(dc.tds_charge, 0) as tds_charge'),
                'dc.updated_at as commission_updated_at',
            ]);

        if ($level !== '' && in_array($level, array_merge($levelDepartmentNames, $legacyLevelDepartmentNames), true)) {
            $qb->where('d.department_name', $level);
        }

        try {
            if ($fromDate !== '' && $toDate !== '') {
                $start = Carbon::parse($fromDate)->startOfDay();
                $end = Carbon::parse($toDate)->endOfDay();
                $qb->whereBetween('dc.updated_at', [$start, $end]);
            } elseif ($fromDate !== '') {
                $start = Carbon::parse($fromDate)->startOfDay();
                $qb->where('dc.updated_at', '>=', $start);
            } elseif ($toDate !== '') {
                $end = Carbon::parse($toDate)->endOfDay();
                $qb->where('dc.updated_at', '<=', $end);
            }
        } catch (\Throwable $e) {
        }

        $qb->orderByRaw("
            CASE d.department_name
                WHEN 'State e-Card Seva' THEN 1
                WHEN 'District e-Card Seva' THEN 2
                WHEN 'Block - e-Card Seva' THEN 3
                WHEN 'G P M e-Card Seva' THEN 4
                WHEN 'e-Card Seva' THEN 5
                WHEN 'State Level' THEN 1
                WHEN 'District Level' THEN 2
                WHEN 'Block Level' THEN 3
                WHEN 'Panchayat Level' THEN 4
                WHEN 'Village Level' THEN 5
                ELSE 99
            END
        ");

        return collect($qb->get());
    }

    public function headings(): array
    {
        return [
            'Department Level',
            'TDS Charge',
            'Last Updated',
        ];
    }

    public function map($row): array
    {
        return [
            (string) ($row->department_name ?? ''),
            number_format((float) ($row->tds_charge ?? 0), 2),
            $row->commission_updated_at ? Carbon::parse($row->commission_updated_at)->format('Y-m-d H:i:s') : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
