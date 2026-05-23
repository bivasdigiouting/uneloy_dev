<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommonSummaryReportController extends Controller
{
    /**
     * Display the Common Summary Report page.
     */
    public function index(Request $request)
    {
        return view('admin.reports.common-summary.index');
    }

    /**
     * Aggregate summary metrics across common modules.
     */
    public function data(Request $request)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        // Helper to apply date filters
        $applyDate = function ($query, $tableAlias = null) use ($from, $to) {
            $col = $tableAlias ? $tableAlias.'.created_at' : 'created_at';
            if (! empty($from)) {
                $query->whereDate($col, '>=', $from);
            }
            if (! empty($to)) {
                $query->whereDate($col, '<=', $to);
            }

            return $query;
        };

        // Wallet Requests (fallback to ecard_wallet_requests if wallet_fund_requests missing)
        $walletCounts = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
        $walletAmount = ['total' => 0.0, 'pending' => 0.0, 'approved' => 0.0, 'rejected' => 0.0];
        if (Schema::hasTable('wallet_fund_requests')) {
            $base = DB::table('wallet_fund_requests as wfr');
            $applyDate($base, 'wfr');
            $walletCounts['total'] = (int) $base->count();
            foreach (['pending', 'approved', 'rejected'] as $st) {
                $q = DB::table('wallet_fund_requests as wfr');
                $applyDate($q, 'wfr');
                $walletCounts[$st] = (int) $q->where('wfr.status', $st)->count();
                $walletAmount[$st] = (float) ($q->sum('wfr.amount') ?? 0);
            }
            $qTotal = DB::table('wallet_fund_requests as wfr');
            $applyDate($qTotal, 'wfr');
            $walletAmount['total'] = (float) ($qTotal->sum('wfr.amount') ?? 0);
        } elseif (Schema::hasTable('ecard_wallet_requests')) {
            $base = DB::table('ecard_wallet_requests as ewr');
            $applyDate($base, 'ewr');
            $walletCounts['total'] = (int) $base->count();
            foreach (['pending', 'approved', 'rejected'] as $st) {
                $q = DB::table('ecard_wallet_requests as ewr');
                $applyDate($q, 'ewr');
                $walletCounts[$st] = (int) $q->where('ewr.status', $st)->count();
                $walletAmount[$st] = (float) ($q->sum('ewr.amount') ?? 0);
            }
            $qTotal = DB::table('ecard_wallet_requests as ewr');
            $applyDate($qTotal, 'ewr');
            $walletAmount['total'] = (float) ($qTotal->sum('ewr.amount') ?? 0);
        }

        // A & R Product Stock Requests
        $arReqCounts = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
        $arReqQuantity = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
        if (Schema::hasTable('ecard_ar_product_stock_requests')) {
            $base = DB::table('ecard_ar_product_stock_requests as ar');
            $applyDate($base, 'ar');
            $arReqCounts['total'] = (int) $base->count();
            $qTotalQty = DB::table('ecard_ar_product_stock_requests as ar');
            $applyDate($qTotalQty, 'ar');
            $arReqQuantity['total'] = (int) ($qTotalQty->sum('ar.quantity') ?? 0);
            foreach (['pending', 'approved', 'rejected'] as $st) {
                $q = DB::table('ecard_ar_product_stock_requests as ar');
                $applyDate($q, 'ar');
                $arReqCounts[$st] = (int) $q->where('ar.status', $st)->count();
                $arReqQuantity[$st] = (int) ($q->sum('ar.quantity') ?? 0);
            }
        }

        // Product Stock Transactions: In/Out quantities
        $stockInQty = 0;
        $stockOutQty = 0;
        if (Schema::hasTable('product_stock_transactions')) {
            $qin = DB::table('product_stock_transactions as pst')->where('pst.type', 'in');
            $applyDate($qin, 'pst');
            $stockInQty = (int) ($qin->sum('pst.quantity') ?? 0);
            $qout = DB::table('product_stock_transactions as pst')->where('pst.type', 'out');
            $applyDate($qout, 'pst');
            $stockOutQty = (int) ($qout->sum('pst.quantity') ?? 0);
        }

        // Product Stock Transfers: count and total quantity
        $transferCount = 0;
        $transferQty = 0.0;
        if (Schema::hasTable('product_stock_transfers')) {
            $qc = DB::table('product_stock_transfers as pst');
            $applyDate($qc, 'pst');
            $transferCount = (int) $qc->count();
            $qq = DB::table('product_stock_transfers as pst');
            $applyDate($qq, 'pst');
            $transferQty = (float) ($qq->sum('pst.quantity') ?? 0);
        }

        return response()->json([
            'cards' => [
                ['key' => 'wallet_requests_count', 'label' => 'Wallet Requests', 'value' => $walletCounts['total']],
                ['key' => 'wallet_amount_total', 'label' => 'Wallet Amount', 'value' => $walletAmount['total']],
                ['key' => 'ar_requests_count', 'label' => 'A & R Stock Requests', 'value' => $arReqCounts['total']],
                ['key' => 'ar_requests_qty', 'label' => 'A & R Quantity', 'value' => $arReqQuantity['total']],
                ['key' => 'stock_in_qty', 'label' => 'Stock In Qty', 'value' => $stockInQty],
                ['key' => 'stock_out_qty', 'label' => 'Stock Out Qty', 'value' => $stockOutQty],
                ['key' => 'stock_transfer_count', 'label' => 'Stock Transfers', 'value' => $transferCount],
                ['key' => 'stock_transfer_qty', 'label' => 'Transfer Quantity', 'value' => $transferQty],
            ],
            'breakdowns' => [
                'wallet_status' => $walletCounts,
                'wallet_amount' => $walletAmount,
                'ar_request_status' => $arReqCounts,
                'ar_request_quantity' => $arReqQuantity,
            ],
        ]);
    }
}
