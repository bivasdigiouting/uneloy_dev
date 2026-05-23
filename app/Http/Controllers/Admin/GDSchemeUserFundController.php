<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class GDSchemeUserFundController extends Controller
{
    public function index(Request $request)
    {
        $schemes = [
            'All',
            'Membership Member',
            'E-StorecMember',
            'E-card Member',
        ];

        return view('admin.benefits.gd-scheme-fund.index', compact('schemes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'scheme' => 'required|string',
            'fund' => 'required|numeric|min:0',
        ]);

        $scheme = $this->normalizeScheme($request->input('scheme'));
        $fundAmount = (float) $request->input('fund');

        // Ensure table exists (runtime-safe)
        if (! Schema::hasTable('gd_scheme_funds')) {
            Schema::create('gd_scheme_funds', function (Blueprint $table) {
                $table->id();
                $table->string('scheme_name');
                $table->decimal('fund_amount', 12, 2);
                $table->timestamps();
            });
        }

        DB::table('gd_scheme_funds')->insert([
            'scheme_name' => $scheme,
            'fund_amount' => $fundAmount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Fund added successfully.']);
    }

    public function data(Request $request)
    {
        $scheme = $this->normalizeScheme($request->input('scheme'));
        $latestFund = 0.00;
        if (Schema::hasTable('gd_scheme_funds')) {
            $row = DB::table('gd_scheme_funds')
                ->where('scheme_name', $scheme)
                ->orderByDesc('created_at')
                ->first();
            $latestFund = $row ? (float) $row->fund_amount : 0.00;
        }

        $schema = DB::getSchemaBuilder();
        $rows = collect();

        $pushRows = function ($collection, $schemeName, $query, $map) use ($latestFund) {
            return $collection->merge(collect($query->limit(1000)->get())->map(function ($row) use ($schemeName, $map, $latestFund) {
                $mapped = $map($row);

                return [
                    'scheme_name' => $schemeName,
                    'user_id' => $mapped['user_id'] ?? '',
                    'user_name' => $mapped['user_name'] ?? '',
                    'mobile_no' => $mapped['mobile_no'] ?? '',
                    'eligible_date' => $mapped['eligible_date'] ?? '',
                    'fund' => number_format($latestFund, 2),
                ];
            }));
        };

        if ($scheme === 'All' || $scheme === 'E-card Member') {
            if ($schema->hasTable('ecard_registrations')) {
                $query = DB::table('ecard_registrations');
                $rows = $pushRows($rows, 'E-card Member', $query, function ($row) {
                    $name = trim(implode(' ', array_filter([data_get($row, 'first_name'), data_get($row, 'middle_name'), data_get($row, 'last_name')])));

                    return [
                        'user_id' => (string) data_get($row, 'aadhaar_no') ?: (string) data_get($row, 'id'),
                        'user_name' => $name,
                        'mobile_no' => (string) data_get($row, 'mobile_no'),
                        'eligible_date' => (string) data_get($row, 'created_at'),
                    ];
                });
            }
        }

        if ($scheme === 'All' || $scheme === 'Membership Member') {
            if ($schema->hasTable('registrations')) {
                $query = DB::table('registrations');
                $dateColumn = $schema->hasColumn('registrations', 'created_at') ? 'created_at' : ($schema->hasColumn('registrations', 'updated_at') ? 'updated_at' : null);
                $rows = $pushRows($rows, 'Membership Member', $query, function ($row) use ($dateColumn) {
                    $name = trim(implode(' ', array_filter([data_get($row, 'first_name'), data_get($row, 'middle_name'), data_get($row, 'last_name')])));

                    return [
                        'user_id' => (string) data_get($row, 'user_id') ?: (string) data_get($row, 'id'),
                        'user_name' => $name,
                        'mobile_no' => (string) data_get($row, 'mobile_no'),
                        'eligible_date' => $dateColumn ? (string) data_get($row, $dateColumn) : (string) data_get($row, 'created_at'),
                    ];
                });
            }
        }

        if ($scheme === 'All' || $scheme === 'E-StorecMember') {
            if ($schema->hasTable('users')) {
                $query = DB::table('users');
                $rows = $pushRows($rows, 'E-StorecMember', $query, function ($row) {
                    return [
                        'user_id' => (string) data_get($row, 'id'),
                        'user_name' => (string) data_get($row, 'name'),
                        'mobile_no' => (string) data_get($row, 'mobile'),
                        'eligible_date' => (string) data_get($row, 'created_at'),
                    ];
                });
            }
        }

        return DataTables::of($rows)
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
    }

    private function normalizeScheme(?string $scheme): string
    {
        $scheme = trim((string) $scheme);
        if ($scheme === '') {
            return 'All';
        }
        // Keep exact labels per request (including typo E-StorecMember)
        $valid = ['All', 'Membership Member', 'E-StorecMember', 'E-card Member'];

        return in_array($scheme, $valid) ? $scheme : 'All';
    }
}
