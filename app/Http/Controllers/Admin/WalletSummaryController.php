<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WalletSummaryController extends Controller
{
    /**
     * Show the User Wallet Summary page or return data via AJAX
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->data($request);
        }

        return view('admin.wallet.summary');
    }

    /**
     * DataTables endpoint: aggregated wallet summary per user
     */
    public function data(Request $request)
    {
        $query = Registration::query()
            ->leftJoin(DB::raw('(
                SELECT 
                    registration_id,
                    SUM(CASE WHEN transaction_type = "add" THEN amount ELSE 0 END) AS cr_amount,
                    SUM(CASE WHEN transaction_type = "remove" THEN amount ELSE 0 END) AS dr_amount
                FROM wallet_transactions
                GROUP BY registration_id
            ) AS wt'), 'wt.registration_id', '=', 'registrations.id')
            ->select([
                'registrations.id',
                'registrations.user_id',
                DB::raw('CONCAT_WS(" ", registrations.first_name, registrations.middle_name, registrations.last_name) AS full_name'),
                'registrations.mobile_no',
                'registrations.email_id',
                DB::raw('COALESCE(wt.cr_amount, 0) AS cr_amount'),
                DB::raw('COALESCE(wt.dr_amount, 0) AS dr_amount'),
                DB::raw('COALESCE(registrations.wallet_balance, 0) AS current_balance'),
            ]);

        // Single combined filter: Id / User ID / Email / Mobile
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $query->where(function ($q) use ($identifier) {
                $q->where('registrations.id', $identifier)
                    ->orWhere('registrations.user_id', $identifier)
                    ->orWhere('registrations.email_id', 'like', '%'.$identifier.'%')
                    ->orWhere('registrations.mobile_no', 'like', '%'.$identifier.'%');
            });
        }

        // Prepare totals (respecting the same filter)
        $totals = [
            'total_cr' => 0,
            'total_dr' => 0,
            'total_current_balance' => 0,
        ];

        $txnTotalsQuery = DB::table('wallet_transactions')
            ->join('registrations', 'wallet_transactions.registration_id', '=', 'registrations.id');
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $txnTotalsQuery->where(function ($q) use ($identifier) {
                $q->where('registrations.id', $identifier)
                    ->orWhere('registrations.user_id', $identifier)
                    ->orWhere('registrations.email_id', 'like', '%'.$identifier.'%')
                    ->orWhere('registrations.mobile_no', 'like', '%'.$identifier.'%');
            });
        }
        $txnTotals = $txnTotalsQuery
            ->selectRaw('SUM(CASE WHEN wallet_transactions.transaction_type = "add" THEN wallet_transactions.amount ELSE 0 END) AS total_cr')
            ->selectRaw('SUM(CASE WHEN wallet_transactions.transaction_type = "remove" THEN wallet_transactions.amount ELSE 0 END) AS total_dr')
            ->first();
        $totals['total_cr'] = (float) ($txnTotals->total_cr ?? 0);
        $totals['total_dr'] = (float) ($txnTotals->total_dr ?? 0);

        $balanceTotalsQuery = Registration::query();
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $balanceTotalsQuery->where(function ($q) use ($identifier) {
                $q->where('id', $identifier)
                    ->orWhere('user_id', $identifier)
                    ->orWhere('email_id', 'like', '%'.$identifier.'%')
                    ->orWhere('mobile_no', 'like', '%'.$identifier.'%');
            });
        }
        $totals['total_current_balance'] = (float) ($balanceTotalsQuery->sum('wallet_balance') ?? 0);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('cr_amount', function ($row) {
                return '₹'.number_format((float) $row->cr_amount, 2);
            })
            ->editColumn('dr_amount', function ($row) {
                return '₹'.number_format((float) $row->dr_amount, 2);
            })
            ->editColumn('current_balance', function ($row) {
                return '₹'.number_format((float) $row->current_balance, 2);
            })
            ->addColumn('action', function ($row) {
                $addFundUrl = route('admin.wallet.management', ['search_id' => $row->id]);
                $viewBtn = '<button type="button" class="btn btn-sm btn-info view-wallet" '.
                    'data-id="'.e($row->id).'" '.
                    'data-user-id="'.e($row->user_id).'" '.
                    'data-name="'.e($row->full_name).'" '.
                    'data-mobile="'.e($row->mobile_no).'" '.
                    'data-email="'.e($row->email_id).'" '.
                    'data-balance="'.e(number_format((float) $row->current_balance, 2)).'">'.
                    '<i class="ti ti-eye"></i> View Wallet Balance</button>';
                $addBtn = '<a href="'.e($addFundUrl).'" class="btn btn-sm btn-primary ms-1">'.
                    '<i class="ti ti-plus"></i> Add Fund</a>';

                return $viewBtn.' '.$addBtn;
            })
            ->rawColumns(['action'])
            ->with([
                'sum_cr' => '₹'.number_format($totals['total_cr'], 2),
                'sum_dr' => '₹'.number_format($totals['total_dr'], 2),
                'sum_current_balance' => '₹'.number_format($totals['total_current_balance'], 2),
            ])
            ->make(true);
    }
}
