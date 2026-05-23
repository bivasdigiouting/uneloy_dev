<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EligibleReportController extends Controller
{
    /**
     * Show Eligible Report page.
     */
    public function index(Request $request)
    {
        $schemes = [
            'All',
            'EPF-E-CARD',
            'ESEVA-E-CARD',
            'BENEFITS E.P.S',
            'BENIFITS 02',
            'BEEFITS 01',
            'SFD-E-CARD',
        ];

        $schemeTypes = ['purchase', 'years'];

        return view('admin.reports.eligible.index', compact('schemes', 'schemeTypes'));
    }

    /**
     * DataTables server-side data for Eligible Report.
     */
    public function data(Request $request)
    {
        $scheme = $this->normalizeScheme($request->input('scheme'));
        $schemeType = $this->normalizeSchemeType($request->input('scheme_type'));
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $searchText = trim((string) $request->input('search_text'));

        $schema = DB::getSchemaBuilder();
        $rows = collect();

        // Helper closures
        $dateBetween = function ($query, $column) use ($fromDate, $toDate) {
            if ($fromDate && $toDate) {
                $query->whereBetween($column, [date('Y-m-d 00:00:00', strtotime($fromDate)), date('Y-m-d 23:59:59', strtotime($toDate))]);
            } elseif ($fromDate) {
                $query->whereDate($column, '>=', date('Y-m-d', strtotime($fromDate)));
            } elseif ($toDate) {
                $query->whereDate($column, '<=', date('Y-m-d', strtotime($toDate)));
            }
        };

        $applySearch = function ($query, array $columns) use ($searchText) {
            if ($searchText !== '') {
                $query->where(function ($q) use ($columns, $searchText) {
                    foreach ($columns as $col) {
                        $q->orWhere($col, 'like', "%$searchText%");
                    }
                });
            }
        };

        // E-Card source
        $ecardRows = collect();
        if ($scheme === 'All' || str_contains($scheme, 'E-CARD')) {
            if ($schema->hasTable('ecard_registrations')) {
                $query = DB::table('ecard_registrations');
                $dateBetween($query, 'created_at');
                $searchCols = ['first_name', 'middle_name', 'last_name', 'mobile_no', 'aadhaar_no', 'gmail_id'];
                $applySearch($query, array_values(array_filter($searchCols, fn ($c) => $schema->hasColumn('ecard_registrations', $c))));

                $ecardRows = collect($query->select('*')->limit(1000)->get())->map(function ($row) use ($scheme, $schemeType) {
                    $name = trim(implode(' ', array_filter([data_get($row, 'first_name'), data_get($row, 'middle_name'), data_get($row, 'last_name')])));
                    $userIdNo = data_get($row, 'aadhaar_no') ?: data_get($row, 'mobile_no') ?: (string) data_get($row, 'id');

                    return [
                        'scheme_name' => $scheme === 'All' ? 'E-Card' : $scheme,
                        'scheme_type' => $schemeType,
                        'user_id_no' => $userIdNo,
                        'user_name' => $name,
                        'eligible_date' => (string) data_get($row, 'created_at'),
                    ];
                });
            }
        }

        // Membership/Benefits source
        $registrationRows = collect();
        if ($scheme === 'All' || str_contains($scheme, 'BENEFITS') || $scheme === 'BENEFITS E.P.S' || str_contains($scheme, 'E.P.S')) {
            if ($schema->hasTable('registrations')) {
                $query = DB::table('registrations');
                $dateColumn = $schema->hasColumn('registrations', 'created_at') ? 'created_at' : ($schema->hasColumn('registrations', 'updated_at') ? 'updated_at' : null);
                if ($dateColumn) {
                    $dateBetween($query, $dateColumn);
                }
                $searchCols = ['first_name', 'middle_name', 'last_name', 'mobile_no', 'user_id', 'gmail_id'];
                $applySearch($query, array_values(array_filter($searchCols, fn ($c) => $schema->hasColumn('registrations', $c))));

                $registrationRows = collect($query->select('*')->limit(1000)->get())->map(function ($row) use ($scheme, $schemeType, $dateColumn) {
                    $name = trim(implode(' ', array_filter([data_get($row, 'first_name'), data_get($row, 'middle_name'), data_get($row, 'last_name')])));
                    $userIdNo = data_get($row, 'user_id') ?: data_get($row, 'mobile_no') ?: (string) data_get($row, 'id');

                    return [
                        'scheme_name' => $scheme === 'All' ? 'Membership' : $scheme,
                        'scheme_type' => $schemeType,
                        'user_id_no' => $userIdNo,
                        'user_name' => $name,
                        'eligible_date' => $dateColumn ? (string) data_get($row, $dateColumn) : (string) data_get($row, 'created_at'),
                    ];
                });
            }
        }

        // Fallback when specific scheme names do not match known sources
        if ($scheme !== 'All' && $ecardRows->isEmpty() && $registrationRows->isEmpty()) {
            if ($schema->hasTable('users')) {
                $query = DB::table('users');
                $dateBetween($query, 'created_at');
                $applySearch($query, ['name', 'email']);
                $rows = collect($query->select('*')->limit(500)->get())->map(function ($row) use ($scheme, $schemeType) {
                    return [
                        'scheme_name' => $scheme,
                        'scheme_type' => $schemeType,
                        'user_id_no' => (string) data_get($row, 'id'),
                        'user_name' => (string) data_get($row, 'name'),
                        'eligible_date' => (string) data_get($row, 'created_at'),
                    ];
                });
            }
        } else {
            $rows = $ecardRows->merge($registrationRows);
        }

        $datatable = DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('eligible_date', function ($row) {
                try {
                    return $row['eligible_date'] ? date('d-M-Y', strtotime($row['eligible_date'])) : '';
                } catch (\Exception $e) {
                    return (string) $row['eligible_date'];
                }
            })
            ->setRowAttr(['style' => 'vertical-align: middle;'])
            ->toJson();

        return $datatable;
    }

    private function normalizeScheme(?string $scheme): string
    {
        $scheme = trim((string) $scheme);
        if ($scheme === '') {
            return 'All';
        }
        // Fix common typos per requirements
        $aliases = [
            'BENIFITS 02' => 'BENIFITS 02',
            'BEEFITS 01' => 'BEEFITS 01',
            'BENEFITS E.P.S' => 'BENEFITS E.P.S',
            'EPF-E-CARD' => 'EPF-E-CARD',
            'ESEVA-E-CARD' => 'ESEVA-E-CARD',
            'SFD-E-CARD' => 'SFD-E-CARD',
            'All' => 'All',
        ];

        return $aliases[$scheme] ?? $scheme;
    }

    private function normalizeSchemeType(?string $type): string
    {
        $type = strtolower(trim((string) $type));

        return in_array($type, ['purchase', 'years']) ? $type : 'purchase';
    }
}
