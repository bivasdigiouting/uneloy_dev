<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpsLevelUserDistribution;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EpsLevelFundReportController extends Controller
{
    public function index()
    {
        return view('admin.eps-level-fund-report.index');
    }

    public function data(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $search = trim($request->input('search'));

        $query = EpsLevelUserDistribution::with(['distribution', 'registration.user'])
            ->when($fromDate, fn ($q) => $q->whereDate('created_at', '>=', $fromDate))
            ->when($toDate, fn ($q) => $q->whereDate('created_at', '<=', $toDate))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('registration.user', function ($u) use ($search) {
                    $u->where('id', $search)
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('level', function ($row) {
                return $this->labelLevel($row->level_type);
            })
            ->addColumn('user_id', function ($row) {
                return optional(optional($row->registration)->user)->id;
            })
            ->addColumn('user_name', function ($row) {
                $user = optional(optional($row->registration)->user);

                return $user->name ?? ($row->registration->business_name ?? '');
            })
            ->addColumn('date', function ($row) {
                return optional($row->distribution)->created_at ? $row->distribution->created_at->format('Y-m-d') : '';
            })
            ->addColumn('distributed_fund', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('total_distributed_fund', function ($row) {
                return number_format(optional($row->distribution)->total_amount ?? 0, 2);
            })
            ->rawColumns(['level'])
            ->make(true);
    }

    protected function labelLevel(string $levelType): string
    {
        $map = [
            'state_level' => 'State',
            'district_level' => 'District',
            'block_level' => 'Block',
            'panchayat_level' => 'Panchayat',
            'village_level' => 'Village',
        ];

        return $map[$levelType] ?? $levelType;
    }
}
