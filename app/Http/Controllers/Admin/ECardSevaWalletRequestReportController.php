<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class ECardSevaWalletRequestReportController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.ecard-seva-wallet-request-report.index');
    }

    public function data(Request $request)
    {
        if (! Schema::hasTable('wallet_fund_requests')) {
            return DataTables::of(collect())->with([
                'totals' => [
                    'pending' => '₹0',
                    'approved' => '₹0',
                    'rejected' => '₹0',
                ],
            ])->make(true);
        }

        $query = DB::table('wallet_fund_requests as wfr')
            ->leftJoin('users as u', 'u.id', '=', 'wfr.user_id')
            ->select([
                'wfr.id as id',
                DB::raw('COALESCE(u.id, wfr.user_id) as reg_user_id'),
                DB::raw("CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) as full_name"),
                'wfr.amount',
                'wfr.status',
                'wfr.transaction_id',
                'wfr.remark',
                'wfr.admin_remark',
                'wfr.created_at',
            ]);

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('wfr.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('wfr.created_at', '<=', $request->input('to_date'));
        }

        // Status filter
        if ($request->filled('request_status')) {
            $query->where('wfr.status', $request->input('request_status'));
        }

        // Search filter (id, email, mobile)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->orWhere('wfr.id', 'like', "%{$search}%")
                    ->orWhere('u.email', 'like', "%{$search}%")
                    ->orWhere('u.mobile', 'like', "%{$search}%");
            });
        }

        // Base for totals (apply date/status filters but ignore search)
        $totalsBase = DB::table('wallet_fund_requests as wfr')
            ->leftJoin('users as u', 'u.id', '=', 'wfr.user_id');
        if ($request->filled('from_date')) {
            $totalsBase->whereDate('wfr.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $totalsBase->whereDate('wfr.created_at', '<=', $request->input('to_date'));
        }
        if ($request->filled('request_status')) {
            $totalsBase->where('wfr.status', $request->input('request_status'));
        }

        $sumPending = (clone $totalsBase)->where('wfr.status', 'pending')->sum('wfr.amount');
        $sumApproved = (clone $totalsBase)->where('wfr.status', 'approved')->sum('wfr.amount');
        $sumRejected = (clone $totalsBase)->where('wfr.status', 'rejected')->sum('wfr.amount');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('amount', function ($row) {
                return '₹'.number_format((float) ($row->amount ?? 0), 2);
            })
            ->editColumn('status', function ($row) {
                $status = $row->status ?? 'pending';
                $class = match ($status) {
                    'approved' => 'badge bg-success',
                    'rejected' => 'badge bg-danger',
                    default => 'badge bg-warning',
                };

                return '<span class="'.$class.'">'.ucfirst($status).'</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? date('d-m-Y H:i', strtotime($row->created_at)) : '';
            })
            ->rawColumns(['status'])
            ->with([
                'totals' => [
                    'pending' => '₹'.number_format((float) $sumPending, 2),
                    'approved' => '₹'.number_format((float) $sumApproved, 2),
                    'rejected' => '₹'.number_format((float) $sumRejected, 2),
                ],
            ])
            ->make(true);
    }
}
