<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class ECardSevaBankSettlementRequestController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.ecard-seva-bank-settlement-requests.index');
    }

    public function data(Request $request)
    {
        try {
            if (! Schema::hasTable('wallet_fund_requests')) {
                return DataTables::of(collect())->make(true);
            }
        } catch (\Exception $e) {
            return DataTables::of(collect())->make(true);
        }

        $query = DB::table('wallet_fund_requests as wfr')
            ->leftJoin('users as u', 'u.id', '=', 'wfr.user_id')
            ->select([
                'wfr.id',
                DB::raw('COALESCE(u.user_id, u.id, wfr.user_id) as reg_user_id'),
                DB::raw("NULLIF(CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name), '') as full_name"),
                'u.name as simple_name',
                'wfr.amount',
                'wfr.status',
                'wfr.remark',
                'wfr.admin_remark',
                'wfr.created_at',
                'u.email',
            ]);

        // Filters
        if ($request->filled('from_date')) {
            $query->whereDate('wfr.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('wfr.created_at', '<=', $request->input('to_date'));
        }
        $status = $request->input('request_status');
        if ($status && in_array(strtolower($status), ['pending', 'approved', 'rejected'])) {
            $query->where('wfr.status', strtolower($status));
        }
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->orWhere('wfr.id', 'like', "%{$search}%")
                    ->orWhere('u.email', 'like', "%{$search}%")
                    ->orWhere('u.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw('COALESCE(u.user_id, u.id, wfr.user_id)'), 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-outline-primary" disabled>View</button>';
            })
            ->addColumn('user_id', function ($row) {
                return (string) ($row->reg_user_id ?? '');
            })
            ->addColumn('user_name', function ($row) {
                $name = $row->full_name ?: $row->simple_name;

                return (string) ($name ?? '');
            })
            ->addColumn('req_date', function ($row) {
                try {
                    return $row->created_at ? date('d-m-Y H:i', strtotime($row->created_at)) : '';
                } catch (\Exception $e) {
                    return (string) ($row->created_at ?? '');
                }
            })
            ->addColumn('withdrawal_amount', function ($row) {
                return '₹'.number_format((float) ($row->amount ?? 0), 2);
            })
            ->addColumn('beneficiary_name', function ($row) {
                // Fallback to user's full name
                $name = $row->full_name ?: $row->simple_name;

                return (string) ($name ?? '');
            })
            ->addColumn('payment_status', function ($row) {
                $status = strtolower($row->status ?? 'pending');
                $class = match ($status) {
                    'approved' => 'badge bg-success',
                    'rejected' => 'badge bg-danger',
                    default => 'badge bg-warning',
                };

                return '<span class="'.$class.'">'.ucfirst($status).'</span>';
            })
            ->editColumn('remark', function ($row) {
                return (string) ($row->remark ?? '');
            })
            ->editColumn('admin_remark', function ($row) {
                return (string) ($row->admin_remark ?? '');
            })
            ->rawColumns(['action', 'payment_status'])
            ->toJson();
    }
}
