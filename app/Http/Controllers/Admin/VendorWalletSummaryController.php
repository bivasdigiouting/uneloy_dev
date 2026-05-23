<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VendorWalletSummaryController extends Controller
{
    /**
     * Show the Vendor Wallet Summary page
     */
    public function index(Request $request)
    {
        return view('admin.vendor-wallet.summary');
    }

    /**
     * DataTables endpoint: aggregated wallet summary per vendor
     */
    public function data(Request $request)
    {
        // Build a safe display name using available columns
        $nameExpr = null;
        if (Schema::hasColumn('vendors', 'business_name')) {
            $nameExpr = 'vendors.business_name';
        }
        if (Schema::hasColumn('vendors', 'contact_person')) {
            // Prefer contact_person if available
            $nameExpr = $nameExpr ? 'COALESCE(vendors.contact_person, '.$nameExpr.')' : 'vendors.contact_person';
        }
        if (Schema::hasColumn('vendors', 'first_name') && Schema::hasColumn('vendors', 'last_name')) {
            $fullNameExpr = 'CONCAT_WS(" ", vendors.first_name'.(Schema::hasColumn('vendors', 'middle_name') ? ', vendors.middle_name' : '').', vendors.last_name)';
            $nameExpr = $nameExpr ? 'COALESCE('.$fullNameExpr.', '.$nameExpr.')' : $fullNameExpr;
        }
        // Fallback to email/mobile to ensure non-null
        $fallbackExpr = 'COALESCE(vendors.gmail_id, vendors.contact_gmail_id, vendors.mobile_no, vendors.contact_mobile_no)';
        $nameExpr = $nameExpr ? 'COALESCE('.$nameExpr.', '.$fallbackExpr.')' : $fallbackExpr;

        $query = Vendor::query()
            ->leftJoin(DB::raw('(
                SELECT 
                    vendor_id,
                    SUM(CASE WHEN transaction_type = "add" THEN amount ELSE 0 END) AS cr_amount,
                    SUM(CASE WHEN transaction_type = "remove" THEN amount ELSE 0 END) AS dr_amount
                FROM vendor_wallet_transactions
                GROUP BY vendor_id
            ) AS vwt'), 'vwt.vendor_id', '=', 'vendors.id')
            ->select([
                'vendors.id',
                DB::raw($nameExpr.' AS display_name'),
                // Use safe mobile/email columns
                DB::raw('COALESCE(vendors.mobile_no, vendors.contact_mobile_no) AS mobile_no'),
                DB::raw('COALESCE(vendors.gmail_id, vendors.contact_gmail_id) AS email_id'),
                DB::raw('COALESCE(vwt.cr_amount, 0) AS cr_amount'),
                DB::raw('COALESCE(vwt.dr_amount, 0) AS dr_amount'),
                DB::raw('COALESCE(vendors.wallet_balance, 0) AS current_balance'),
            ]);

        // Combined filter: Id / Name / Email / Mobile
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $query->where(function ($q) use ($identifier) {
                $q->where('vendors.id', $identifier)
                    ->orWhere('vendors.gmail_id', 'like', '%'.$identifier.'%')
                    ->orWhere('vendors.contact_gmail_id', 'like', '%'.$identifier.'%')
                    ->orWhere('vendors.mobile_no', 'like', '%'.$identifier.'%')
                    ->orWhere('vendors.contact_mobile_no', 'like', '%'.$identifier.'%');
                if (Schema::hasColumn('vendors', 'business_name')) {
                    $q->orWhere('vendors.business_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'contact_person')) {
                    $q->orWhere('vendors.contact_person', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'first_name')) {
                    $q->orWhere('vendors.first_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'middle_name')) {
                    $q->orWhere('vendors.middle_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'last_name')) {
                    $q->orWhere('vendors.last_name', 'like', '%'.$identifier.'%');
                }
            });
        }

        // Totals respecting same filter
        $totals = [
            'total_cr' => 0,
            'total_dr' => 0,
            'total_current_balance' => 0,
        ];

        $txnTotalsQuery = DB::table('vendor_wallet_transactions')
            ->join('vendors', 'vendor_wallet_transactions.vendor_id', '=', 'vendors.id');
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $txnTotalsQuery->where(function ($q) use ($identifier) {
                $q->where('vendors.id', $identifier)
                    ->orWhere('vendors.gmail_id', 'like', '%'.$identifier.'%')
                    ->orWhere('vendors.contact_gmail_id', 'like', '%'.$identifier.'%')
                    ->orWhere('vendors.mobile_no', 'like', '%'.$identifier.'%')
                    ->orWhere('vendors.contact_mobile_no', 'like', '%'.$identifier.'%');
                if (Schema::hasColumn('vendors', 'business_name')) {
                    $q->orWhere('vendors.business_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'contact_person')) {
                    $q->orWhere('vendors.contact_person', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'first_name')) {
                    $q->orWhere('vendors.first_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'middle_name')) {
                    $q->orWhere('vendors.middle_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'last_name')) {
                    $q->orWhere('vendors.last_name', 'like', '%'.$identifier.'%');
                }
            });
        }
        $txnTotals = $txnTotalsQuery
            ->selectRaw('SUM(CASE WHEN vendor_wallet_transactions.transaction_type = "add" THEN vendor_wallet_transactions.amount ELSE 0 END) AS total_cr')
            ->selectRaw('SUM(CASE WHEN vendor_wallet_transactions.transaction_type = "remove" THEN vendor_wallet_transactions.amount ELSE 0 END) AS total_dr')
            ->first();
        $totals['total_cr'] = (float) ($txnTotals->total_cr ?? 0);
        $totals['total_dr'] = (float) ($txnTotals->total_dr ?? 0);

        $balanceTotalsQuery = Vendor::query();
        if ($request->filled('search_id')) {
            $identifier = trim($request->input('search_id'));
            $balanceTotalsQuery->where(function ($q) use ($identifier) {
                $q->where('id', $identifier)
                    ->orWhere('gmail_id', 'like', '%'.$identifier.'%')
                    ->orWhere('contact_gmail_id', 'like', '%'.$identifier.'%')
                    ->orWhere('mobile_no', 'like', '%'.$identifier.'%')
                    ->orWhere('contact_mobile_no', 'like', '%'.$identifier.'%');
                if (Schema::hasColumn('vendors', 'business_name')) {
                    $q->orWhere('business_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'contact_person')) {
                    $q->orWhere('contact_person', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'first_name')) {
                    $q->orWhere('first_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'middle_name')) {
                    $q->orWhere('middle_name', 'like', '%'.$identifier.'%');
                }
                if (Schema::hasColumn('vendors', 'last_name')) {
                    $q->orWhere('last_name', 'like', '%'.$identifier.'%');
                }
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
                $addFundUrl = route('admin.vendor.wallet.management', ['search_id' => $row->id]);
                $viewBtn = '<button type="button" class="btn btn-sm btn-info view-vendor-wallet" '
                    .'data-id="'.e($row->id).'" '
                    .'data-name="'.e($row->display_name).'" '
                    .'data-mobile="'.e($row->mobile_no).'" '
                    .'data-email="'.e($row->email_id).'" '
                    .'data-balance="'.e(number_format((float) $row->current_balance, 2)).'">'
                    .'<i class="ti ti-eye"></i> View</button>';
                $addBtn = '<a href="'.e($addFundUrl).'" class="btn btn-sm btn-primary ms-1">'
                    .'<i class="ti ti-plus"></i> Add Fund</a>';

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

    /**
     * Transactions endpoint for a vendor (for modal view)
     */
    public function transactions(Request $request, int $vendorId)
    {
        $transactions = VendorWalletTransaction::with('performedByUser')
            ->where('vendor_id', $vendorId)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function ($txn) {
                return [
                    'date' => optional($txn->created_at)->format('Y-m-d H:i'),
                    'type' => $txn->transaction_type,
                    'amount' => '?'.number_format((float) $txn->amount, 2),
                    'previous_balance' => '?'.number_format((float) $txn->previous_balance, 2),
                    'new_balance' => '?'.number_format((float) $txn->new_balance, 2),
                    'narration' => $txn->narration,
                    'performed_by' => optional($txn->performedByUser)->name ?? '-',
                ];
            });

        return response()->json([
            'data' => $transactions,
        ]);
    }
}
