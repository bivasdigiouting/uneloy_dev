<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpsUserFund;
use App\Models\Registration;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EpsGlobalDisbursReportController extends Controller
{
    /**
     * Show the Global Disburs. Level Fund Report page.
     */
    public function index(Request $request)
    {
        $fundTypes = [
            'Global Distribute User Fund' => 'Global Distribute User Fund',
        ];
        $userTypes = [
            'recharge_1' => 'RECHARGE 1',
            'recharge_2' => 'RECHARGE 2',
            'deactivate' => 'DEACTIVATE',
        ];

        return view('admin.membership.eps-global-disburs-report.index', compact('fundTypes', 'userTypes'));
    }

    /**
     * DataTables endpoint: List users with latest and total distributed fund for selected type and date range.
     */
    public function data(Request $request)
    {
        $fundType = trim((string) $request->input('fund_type'));
        $userType = trim((string) $request->input('user_type'));
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $searchText = trim((string) $request->input('search_text'));

        $query = Registration::query()
            ->select([
                'registrations.id',
                'registrations.user_id',
                'registrations.first_name',
                'registrations.middle_name',
                'registrations.last_name',
                'registrations.gmail_id',
                'registrations.mobile_no',
                'registrations.status',
            ]);

        if ($searchText !== '') {
            $query->where(function ($q) use ($searchText) {
                $q->orWhere('registrations.id', 'like', "%$searchText%")
                    ->orWhere('registrations.user_id', 'like', "%$searchText%")
                    ->orWhere('registrations.gmail_id', 'like', "%$searchText%")
                    ->orWhere('registrations.mobile_no', 'like', "%$searchText%");
            });
        }

        // Compute latest distributed fund and total distributed fund for the selected filters
        $latestAmount = 0.0;
        $totalAmount = 0.0;
        $latestDate = null;

        if (in_array($userType, ['recharge_1', 'recharge_2', 'deactivate'])) {
            $fundQb = EpsUserFund::query()->where('user_type', $userType);
            if ($fundType !== '' && $fundType !== 'All') {
                $fundQb->where('fund_type', $fundType);
            }
            if ($fromDate && $toDate) {
                $fundQb->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($fromDate)), date('Y-m-d 23:59:59', strtotime($toDate))]);
            } elseif ($fromDate) {
                $fundQb->whereDate('created_at', '>=', date('Y-m-d', strtotime($fromDate)));
            } elseif ($toDate) {
                $fundQb->whereDate('created_at', '<=', date('Y-m-d', strtotime($toDate)));
            }

            $latestRow = (clone $fundQb)->orderByDesc('id')->first();
            $latestAmount = $latestRow ? (float) $latestRow->amount : 0.0;
            $latestDate = $latestRow && $latestRow->created_at ? date('d-M-Y', strtotime($latestRow->created_at)) : '';
            $totalAmount = (float) $fundQb->sum('amount');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_id', function ($row) {
                return $row->user_id ?: (string) $row->id;
            })
            ->addColumn('user_name', function ($row) {
                $full = trim(($row->first_name ?: '').' '.($row->middle_name ?: '').' '.($row->last_name ?: ''));

                return $full !== '' ? $full : 'N/A';
            })
            ->addColumn('status', function ($row) {
                return $row->status ?: 'N/A';
            })
            ->addColumn('date', function () use ($latestDate) {
                return $latestDate;
            })
            ->addColumn('distributed_fund', function () use ($latestAmount) {
                return number_format($latestAmount, 2);
            })
            ->addColumn('total_distributed_fund', function () use ($totalAmount) {
                return number_format($totalAmount, 2);
            })
            ->rawColumns([])
            ->make(true);
    }
}
