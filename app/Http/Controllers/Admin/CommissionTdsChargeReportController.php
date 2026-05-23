<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CommissionTdsChargeReportExport;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class CommissionTdsChargeReportController extends Controller
{
    private const LEVEL_DEPARTMENT_NAMES = [
        'State e-Card Seva',
        'District e-Card Seva',
        'Block - e-Card Seva',
        'G P M e-Card Seva',
        'e-Card Seva',
    ];
    private const LEGACY_LEVEL_DEPARTMENT_NAMES = [
        'State Level',
        'District Level',
        'Block Level',
        'Panchayat Level',
        'Village Level',
    ];
    private const LEVEL_DEPARTMENT_RENAMES = [
        'State Level' => 'State e-Card Seva',
        'District Level' => 'District e-Card Seva',
        'Block Level' => 'Block - e-Card Seva',
        'Panchayat Level' => 'G P M e-Card Seva',
        'Village Level' => 'e-Card Seva',
    ];

    public function index(Request $request)
    {
        $filters = $this->normalizeFilters($request);

        $this->ensureLevelDepartmentsExist();

        $rows = $this->buildQuery($filters)->get();

        $summary = [
            'count' => $rows->count(),
            'total_tds_charge' => (float) $rows->sum('tds_charge'),
        ];

        $exportParams = array_filter($filters, static fn ($v) => $v !== null && $v !== '');

        return view('admin.commission-tds-charge-report.index', [
            'rows' => $rows,
            'filters' => $filters,
            'summary' => $summary,
            'exportParams' => $exportParams,
            'levelDepartmentNames' => self::LEVEL_DEPARTMENT_NAMES,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $fileName = 'commission_tds_charge_report_'.Carbon::now()->format('Ymd_His').'.xlsx';

        return Excel::download(new CommissionTdsChargeReportExport($filters), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $fileName = 'commission_tds_charge_report_'.Carbon::now()->format('Ymd_His').'.pdf';

        return Excel::download(new CommissionTdsChargeReportExport($filters), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    }

    private function normalizeFilters(Request $request): array
    {
        $level = trim((string) $request->query('level', ''));
        if ($level !== '' && ! in_array($level, self::LEVEL_DEPARTMENT_NAMES, true)) {
            $level = '';
        }

        return [
            'level' => $level,
            'from_date' => trim((string) $request->query('from_date', '')),
            'to_date' => trim((string) $request->query('to_date', '')),
        ];
    }

    private function buildQuery(array $filters)
    {
        $hasDepartments = Schema::hasTable('departments');
        $hasDepartmentCommissions = Schema::hasTable('department_commissions');

        if (! $hasDepartments || ! $hasDepartmentCommissions) {
            return DB::query()->fromRaw('(select 1 as id) as empty')->whereRaw('1 = 0');
        }

        $qb = DB::table('departments as d')
            ->leftJoin('department_commissions as dc', 'dc.department_id', '=', 'd.id')
            ->whereIn('d.department_name', array_merge(self::LEVEL_DEPARTMENT_NAMES, self::LEGACY_LEVEL_DEPARTMENT_NAMES))
            ->select([
                'd.id as department_id',
                'd.department_name',
                DB::raw('COALESCE(dc.tds_charge, 0) as tds_charge'),
                'dc.updated_at as commission_updated_at',
            ]);

        if (! empty($filters['level'])) {
            $qb->where('d.department_name', $filters['level']);
        }

        $fromDate = trim((string) ($filters['from_date'] ?? ''));
        $toDate = trim((string) ($filters['to_date'] ?? ''));

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

        return $qb->orderByRaw("
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
    }

    private function ensureLevelDepartmentsExist(): void
    {
        if (! Schema::hasTable('departments')) {
            return;
        }

        foreach (self::LEVEL_DEPARTMENT_RENAMES as $old => $new) {
            Department::where('department_name', $old)->update(['department_name' => $new]);
        }

        foreach (self::LEVEL_DEPARTMENT_NAMES as $name) {
            Department::firstOrCreate(
                ['department_name' => $name],
                ['is_active' => true]
            );
        }
    }
}
