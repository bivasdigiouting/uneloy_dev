<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VendorWalletRequestReportController extends Controller
{
    /**
     * Show the Vendor Wallet Request Report page
     */
    public function index(Request $request)
    {
        return view('admin.vendor-wallet.request-report');
    }

    /**
     * DataTables endpoint: Vendor Wallet Fund Request list with filters and totals
     */
    public function data(Request $request)
    {
        // Validate required tables/columns exist; otherwise return empty dataset
        if (! Schema::hasTable('wallet_fund_requests') || ! Schema::hasTable('vendors') || ! Schema::hasColumn('wallet_fund_requests', 'vendor_id')) {
            return DataTables::of(collect())
                ->with([
                    'totals' => [
                        'pending' => '₹0.00',
                        'approved' => '₹0.00',
                        'rejected' => '₹0.00',
                    ],
                ])
                ->make(true);
        }

        // Build robust display name from available vendor columns
        $nameExpr = null;
        if (Schema::hasColumn('vendors', 'business_name')) {
            $nameExpr = 'v.business_name';
        }
        if (Schema::hasColumn('vendors', 'contact_person')) {
            $nameExpr = $nameExpr ? 'COALESCE(v.contact_person, '.$nameExpr.')' : 'v.contact_person';
        }
        if (Schema::hasColumn('vendors', 'first_name') && Schema::hasColumn('vendors', 'last_name')) {
            $fullNameExpr = 'CONCAT_WS(" ", v.first_name'.(Schema::hasColumn('vendors', 'middle_name') ? ', v.middle_name' : '').', v.last_name)';
            $nameExpr = $nameExpr ? 'COALESCE('.$fullNameExpr.', '.$nameExpr.')' : $fullNameExpr;
        }
        $fallbackExpr = 'COALESCE(v.gmail_id, v.contact_gmail_id, v.mobile_no, v.contact_mobile_no)';
        $nameExpr = $nameExpr ? 'COALESCE('.$nameExpr.', '.$fallbackExpr.')' : $fallbackExpr;

        $query = DB::table('wallet_fund_requests as wfr')
            ->leftJoin('vendors as v', 'v.id', '=', 'wfr.vendor_id')
            ->select([
                'wfr.id as id',
                'wfr.transaction_id',
                'wfr.amount',
                'wfr.status', // pending | approved | rejected
                'wfr.remark',
                'wfr.admin_remark',
                'wfr.created_at',
                'v.id as vendor_id',
                DB::raw($nameExpr.' as vendor_name'),
            ]);

        // Date range filter (created_at)
        if ($request->filled('from_date')) {
            $query->whereDate('wfr.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('wfr.created_at', '<=', $request->input('to_date'));
        }

        // Request Status filter (All | Pending | Approved | Rejected)
        if ($request->filled('request_status')) {
            $status = strtolower(trim($request->input('request_status')));
            if (in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('wfr.status', $status);
            }
        }

        // Search by Vendor Id / Name / Email / Mobile using single input
        $searchValue = trim($request->input('search_by', ''));
        if ($searchValue !== '') {
            $query->where(function ($q) use ($searchValue) {
                $q->where('v.id', $searchValue)
                    ->orWhere('v.gmail_id', 'like', '%'.$searchValue.'%')
                    ->orWhere('v.contact_gmail_id', 'like', '%'.$searchValue.'%')
                    ->orWhere('v.mobile_no', 'like', '%'.$searchValue.'%')
                    ->orWhere('v.contact_mobile_no', 'like', '%'.$searchValue.'%');
                if (Schema::hasColumn('vendors', 'business_name')) {
                    $q->orWhere('v.business_name', 'like', '%'.$searchValue.'%');
                }
                if (Schema::hasColumn('vendors', 'contact_person')) {
                    $q->orWhere('v.contact_person', 'like', '%'.$searchValue.'%');
                }
                if (Schema::hasColumn('vendors', 'first_name')) {
                    $q->orWhere('v.first_name', 'like', '%'.$searchValue.'%');
                }
                if (Schema::hasColumn('vendors', 'middle_name')) {
                    $q->orWhere('v.middle_name', 'like', '%'.$searchValue.'%');
                }
                if (Schema::hasColumn('vendors', 'last_name')) {
                    $q->orWhere('v.last_name', 'like', '%'.$searchValue.'%');
                }
            });
        }

        // Order by newest request first
        $query->orderByDesc('wfr.created_at');

        // Totals base respecting same filters (but ignore search for overall totals)
        $totalsBase = DB::table('wallet_fund_requests as wfr')
            ->leftJoin('vendors as v', 'v.id', '=', 'wfr.vendor_id');
        if ($request->filled('from_date')) {
            $totalsBase->whereDate('wfr.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $totalsBase->whereDate('wfr.created_at', '<=', $request->input('to_date'));
        }
        if ($request->filled('request_status')) {
            $status = strtolower(trim($request->input('request_status')));
            if (in_array($status, ['pending', 'approved', 'rejected'])) {
                $totalsBase->where('wfr.status', $status);
            }
        }

        $sumPending = (float) ((clone $totalsBase)->where('wfr.status', 'pending')->sum('wfr.amount') ?? 0);
        $sumApproved = (float) ((clone $totalsBase)->where('wfr.status', 'approved')->sum('wfr.amount') ?? 0);
        $sumRejected = (float) ((clone $totalsBase)->where('wfr.status', 'rejected')->sum('wfr.amount') ?? 0);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('id_no', function ($row) {
                return (string) ($row->vendor_id ?? '');
            })
            ->addColumn('name', function ($row) {
                return (string) ($row->vendor_name ?? '');
            })
            ->editColumn('amount', function ($row) {
                return '₹'.number_format((float) ($row->amount ?? 0), 2);
            })
            ->addColumn('payment_status', function ($row) {
                $label = ucfirst($row->status ?? 'pending');
                $color = match ($row->status) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                };

                return '<span class="badge bg-'.$color.'">'.e($label).'</span>';
            })
            ->addColumn('req_date', function ($row) {
                try {
                    return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M Y, h:i A') : '';
                } catch (\Throwable $e) {
                    return (string) ($row->created_at ?? '');
                }
            })
            ->addColumn('action', function ($row) {
                $viewBtn = '<button type="button" class="btn btn-sm btn-info" disabled><i class="ti ti-eye"></i> View</button>';
                $approveBtn = '<button type="button" class="btn btn-sm btn-success ms-1" disabled><i class="ti ti-check"></i> Approve</button>';
                $rejectBtn = '<button type="button" class="btn btn-sm btn-danger ms-1" disabled><i class="ti ti-x"></i> Reject</button>';

                return $viewBtn.' '.$approveBtn.' '.$rejectBtn;
            })
            ->rawColumns(['payment_status', 'action'])
            ->with([
                'totals' => [
                    'pending' => '₹'.number_format($sumPending, 2),
                    'approved' => '₹'.number_format($sumApproved, 2),
                    'rejected' => '₹'.number_format($sumRejected, 2),
                ],
            ])
            ->make(true);
    }
}
