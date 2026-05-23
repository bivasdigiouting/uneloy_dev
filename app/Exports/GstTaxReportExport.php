<?php

namespace App\Exports;

use App\Models\GstTax;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GstTaxReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private array $filters = []) {}

    public function collection(): Collection
    {
        $qb = GstTax::query()
            ->select(['id', 'tax_name', 'rate_percent', 'is_active', 'created_at', 'updated_at']);

        $status = trim((string) ($this->filters['status'] ?? ''));
        if ($status === 'active') {
            $qb->where('is_active', true);
        } elseif ($status === 'inactive') {
            $qb->where('is_active', false);
        }

        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $qb->where('tax_name', 'like', '%'.$search.'%');
        }

        $minRate = $this->filters['min_rate'] ?? null;
        $maxRate = $this->filters['max_rate'] ?? null;

        if (is_numeric($minRate)) {
            $qb->where('rate_percent', '>=', (float) $minRate);
        }

        if (is_numeric($maxRate)) {
            $qb->where('rate_percent', '<=', (float) $maxRate);
        }

        return $qb->orderBy('rate_percent')->orderBy('tax_name')->get();
    }

    public function headings(): array
    {
        return [
            'Tax Name',
            'GST Rate (%)',
            'CGST Rate (%)',
            'SGST Rate (%)',
            'IGST Rate (%)',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    public function map($row): array
    {
        $rate = (float) ($row->rate_percent ?? 0);
        $cgst = $rate / 2;
        $sgst = $rate / 2;
        $igst = $rate;

        return [
            (string) ($row->tax_name ?? ''),
            number_format($rate, 2),
            number_format($cgst, 2),
            number_format($sgst, 2),
            number_format($igst, 2),
            ($row->is_active ?? false) ? 'Active' : 'Inactive',
            $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d H:i:s') : '',
            $row->updated_at ? Carbon::parse($row->updated_at)->format('Y-m-d H:i:s') : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
