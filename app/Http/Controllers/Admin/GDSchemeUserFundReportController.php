<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class GDSchemeUserFundReportController extends Controller
{
    public function index(Request $request)
    {
        $schemes = [
            'ALL',
            'EPF-E-CARD',
            'Eseva E-CARD',
            'BENEFITS E.P.S',
            'BENEFITS 02',
            'BENEFITS 01',
            'SFD-E-CARD',
        ];

        return view('admin.benefits.scheme-user-fund-report.index', compact('schemes'));
    }

    public function data(Request $request)
    {
        $scheme = $this->normalizeScheme($request->input('scheme'));
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $searchField = $request->input('search_field'); // 'user_id' | 'user_name' | null
        $searchValue = trim((string) $request->input('search_value'));

        $schema = DB::getSchemaBuilder();

        // Map report schemes to internal fund categories
        $fundCategory = $this->mapSchemeToFundCategory($scheme); // 'E-card Member' | 'Membership Member' | null

        // Latest fund and total fund within date range for the mapped category
        $latestFund = 0.00;
        $totalFund = 0.00;
        if ($fundCategory && Schema::hasTable('gd_scheme_funds')) {
            $fundQuery = DB::table('gd_scheme_funds')->where('scheme_name', $fundCategory);
            if ($fromDate) {
                $fundQuery->whereDate('created_at', '>=', $fromDate);
            }
            if ($toDate) {
                $fundQuery->whereDate('created_at', '<=', $toDate);
            }

            $latestRow = (clone $fundQuery)->orderByDesc('created_at')->first();
            $latestFund = $latestRow ? (float) $latestRow->fund_amount : 0.00;

            $totalFund = (float) $fundQuery->sum('fund_amount');
        }

        $rows = collect();

        // Helper to push normalized rows from a query
        $pushRows = function ($collection, $schemeLabel, $query, $map, $dateColumn = 'created_at') use ($latestFund, $totalFund, $fromDate, $toDate, $searchField, $searchValue) {
            if ($fromDate && $dateColumn) {
                $query->whereDate($dateColumn, '>=', $fromDate);
            }
            if ($toDate && $dateColumn) {
                $query->whereDate($dateColumn, '<=', $toDate);
            }

            // Basic search filter
            if ($searchValue !== '') {
                if ($searchField === 'user_id') {
                    $query->where(function ($q) {
                        // Try common id fields across sources
                        $q->orWhere('id', 'like', DB::raw("CONCAT('%', ?, '%')"));
                    });
                } elseif ($searchField === 'user_name') {
                    // Apply to common name fields if present
                    $query->where(function ($q) {
                        $q->orWhere('name', 'like', DB::raw("CONCAT('%', ?, '%')"));
                    });
                }
                // Fallback: we will handle precise field mapping in the map callback using PHP filter if needed
            }

            $data = collect($query->limit(1000)->get())->map(function ($row) use ($schemeLabel, $map, $latestFund, $totalFund, $searchField, $searchValue) {
                $mapped = $map($row);
                // Additional search filtering at PHP level to handle source-specific fields
                if ($searchValue !== '') {
                    if ($searchField === 'user_id' && stripos((string) ($mapped['user_id'] ?? ''), $searchValue) === false) {
                        return null;
                    }
                    if ($searchField === 'user_name' && stripos((string) ($mapped['user_name'] ?? ''), $searchValue) === false) {
                        return null;
                    }
                }

                return [
                    'scheme_name' => $schemeLabel,
                    'user_id' => $mapped['user_id'] ?? '',
                    'user_name' => $mapped['user_name'] ?? '',
                    'date' => $mapped['date'] ?? '',
                    'distribute_fund' => number_format($latestFund, 2),
                    'total_distribute_fund' => number_format($totalFund, 2),
                ];
            })->filter();

            return $collection->merge($data);
        };

        // Data sources mapping
        if ($scheme === 'ALL' || in_array($scheme, ['EPF-E-CARD', 'Eseva E-CARD', 'SFD-E-CARD'])) {
            if ($schema->hasTable('ecard_registrations')) {
                $query = DB::table('ecard_registrations');
                $rows = $pushRows($rows, 'E-Card', $query, function ($row) {
                    $name = trim(implode(' ', array_filter([data_get($row, 'first_name'), data_get($row, 'middle_name'), data_get($row, 'last_name')])));

                    return [
                        'user_id' => (string) data_get($row, 'aadhaar_no') ?: (string) data_get($row, 'id'),
                        'user_name' => $name,
                        'date' => (string) data_get($row, 'created_at'),
                    ];
                }, 'created_at');
            }
        }

        if ($scheme === 'ALL' || in_array($scheme, ['BENEFITS E.P.S', 'BENEFITS 02', 'BENEFITS 01'])) {
            if ($schema->hasTable('registrations')) {
                $dateColumn = $schema->hasColumn('registrations', 'created_at') ? 'created_at' : ($schema->hasColumn('registrations', 'updated_at') ? 'updated_at' : null);
                $query = DB::table('registrations');
                $rows = $pushRows($rows, 'Membership', $query, function ($row) use ($dateColumn) {
                    $name = trim(implode(' ', array_filter([data_get($row, 'first_name'), data_get($row, 'middle_name'), data_get($row, 'last_name')])));

                    return [
                        'user_id' => (string) data_get($row, 'user_id') ?: (string) data_get($row, 'id'),
                        'user_name' => $name,
                        'date' => $dateColumn ? (string) data_get($row, $dateColumn) : (string) data_get($row, 'created_at'),
                    ];
                }, $dateColumn ?: 'created_at');
            }
        }

        if ($rows->isEmpty() && $schema->hasTable('users') && ($scheme === 'ALL' || $scheme === 'SFD-E-CARD')) {
            // As a fallback include users table for SFD-E-CARD or ALL
            $query = DB::table('users');
            $rows = $pushRows($rows, 'Users', $query, function ($row) {
                return [
                    'user_id' => (string) data_get($row, 'id'),
                    'user_name' => (string) data_get($row, 'name'),
                    'date' => (string) data_get($row, 'created_at'),
                ];
            }, 'created_at');
        }

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('date', function ($row) {
                try {
                    return $row['date'] ? date('d-M-Y', strtotime($row['date'])) : '';
                } catch (\Exception $e) {
                    return (string) $row['date'];
                }
            })
            ->setRowAttr(['style' => 'vertical-align: middle;'])
            ->toJson();
    }

    private function normalizeScheme(?string $scheme): string
    {
        $scheme = trim((string) $scheme);
        if ($scheme === '') {
            return 'ALL';
        }
        $valid = ['ALL', 'EPF-E-CARD', 'Eseva E-CARD', 'BENEFITS E.P.S', 'BENEFITS 02', 'BENEFITS 01', 'SFD-E-CARD'];

        return in_array($scheme, $valid) ? $scheme : 'ALL';
    }

    private function mapSchemeToFundCategory(string $scheme): ?string
    {
        // Map report schemes to internal fund categories used in gd_scheme_funds
        // We currently store funds under 'E-card Member' and 'Membership Member'
        if (in_array($scheme, ['EPF-E-CARD', 'Eseva E-CARD', 'SFD-E-CARD'])) {
            return 'E-card Member';
        }
        if (in_array($scheme, ['BENEFITS E.P.S', 'BENEFITS 02', 'BENEFITS 01'])) {
            return 'Membership Member';
        }

        // ALL -> no single category; returning null will show 0 totals
        return null;
    }
}
