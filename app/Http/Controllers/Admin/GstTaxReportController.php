<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GstTaxReportExport;
use App\Http\Controllers\Controller;
use App\Models\GstTax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GstTaxReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $rows = $this->buildQuery($filters)->get();

        $summary = [
            'count' => $rows->count(),
            'active' => $rows->where('is_active', true)->count(),
            'inactive' => $rows->where('is_active', false)->count(),
        ];

        $exportParams = array_filter($filters, static fn ($v) => $v !== null && $v !== '');

        return view('admin.gst-tax-report.index', [
            'rows' => $rows,
            'filters' => $filters,
            'summary' => $summary,
            'exportParams' => $exportParams,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $fileName = 'gst_tax_report_'.Carbon::now()->format('Ymd_His').'.xlsx';

        return Excel::download(new GstTaxReportExport($filters), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $fileName = 'gst_tax_report_'.Carbon::now()->format('Ymd_His').'.pdf';

        return Excel::download(new GstTaxReportExport($filters), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    }

    private function normalizeFilters(Request $request): array
    {
        $status = trim((string) $request->query('status', ''));
        if ($status !== '' && ! in_array($status, ['active', 'inactive'], true)) {
            $status = '';
        }

        $minRate = $request->query('min_rate', '');
        $maxRate = $request->query('max_rate', '');

        return [
            'status' => $status,
            'search' => trim((string) $request->query('search', '')),
            'min_rate' => is_numeric($minRate) ? (float) $minRate : null,
            'max_rate' => is_numeric($maxRate) ? (float) $maxRate : null,
        ];
    }

    private function buildQuery(array $filters)
    {
        $qb = GstTax::query()
            ->select(['id', 'tax_name', 'rate_percent', 'is_active', 'created_at', 'updated_at']);

        if (($filters['status'] ?? '') === 'active') {
            $qb->where('is_active', true);
        } elseif (($filters['status'] ?? '') === 'inactive') {
            $qb->where('is_active', false);
        }

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $qb->where('tax_name', 'like', '%'.$search.'%');
        }

        if (isset($filters['min_rate']) && $filters['min_rate'] !== null) {
            $qb->where('rate_percent', '>=', (float) $filters['min_rate']);
        }

        if (isset($filters['max_rate']) && $filters['max_rate'] !== null) {
            $qb->where('rate_percent', '<=', (float) $filters['max_rate']);
        }

        return $qb->orderBy('rate_percent')->orderBy('tax_name');
    }
}
