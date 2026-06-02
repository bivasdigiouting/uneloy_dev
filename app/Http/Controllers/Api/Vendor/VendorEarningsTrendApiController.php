<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorEarningsTrendApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    private function resolveEarningSourceTable(): ?string
    {
        // Best-effort: prefer wallet transactions if present.
        if (Schema::hasTable('vendor_wallet_transactions')) {
            return 'vendor_wallet_transactions';
        }
        // Fallback: derive from orders.
        if (Schema::hasTable('vendor_orders')) {
            return 'vendor_orders';
        }
        if (Schema::hasTable('orders')) {
            return 'orders';
        }
        if (Schema::hasTable('user_orders')) {
            return 'user_orders';
        }
        return null;
    }

    private function resolveWalletColumns(string $table): array
    {
        $amountCol = Schema::hasColumn($table, 'amount') ? 'amount' : (Schema::hasColumn($table, 'value') ? 'value' : null);
        $typeCol = Schema::hasColumn($table, 'transaction_type') ? 'transaction_type' : (Schema::hasColumn($table, 'type') ? 'type' : null);
        $dateCol = 'created_at';
        if (! Schema::hasColumn($table, $dateCol)) {
            foreach (['date', 'transaction_date', 'created_on'] as $d) {
                if (Schema::hasColumn($table, $d)) {
                    $dateCol = $d;
                    break;
                }
            }
        }
        return compact('amountCol', 'typeCol', 'dateCol');
    }

    public function dailyTrend(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $range = strtolower((string) $request->query('range', 'monthly'));
        $from = null;
        $to = null;

        if ($range === 'today') {
            $from = Carbon::today()->toDateString();
            $to = Carbon::today()->toDateString();
        } elseif ($range === 'monthly') {
            $from = Carbon::now()->startOfMonth()->toDateString();
            $to = Carbon::now()->endOfMonth()->toDateString();
        } elseif ($range === 'yearly') {
            $from = Carbon::now()->startOfYear()->toDateString();
            $to = Carbon::now()->endOfYear()->toDateString();
        } elseif ($range === 'custom') {
            $request->validate(['from' => 'required|date', 'to' => 'required|date|after_or_equal:from']);
            $from = Carbon::parse($request->query('from'))->toDateString();
            $to = Carbon::parse($request->query('to'))->toDateString();
        } else {
            $request->validate(['range' => 'in:today,monthly,yearly,custom']);
        }

        $table = $this->resolveEarningSourceTable();
        if (! $table) {
            return response()->json(['success' => false, 'message' => 'No earning source table found'], 500);
        }

        // Wallet-based trend
        if ($table === 'vendor_wallet_transactions') {
            $cols = $this->resolveWalletColumns($table);
            if (! $cols['amountCol'] || ! $cols['typeCol']) {
                return response()->json(['success' => false, 'message' => 'Wallet transaction columns missing'], 500);
            }

            $vendorFk = Schema::hasColumn($table, 'vendor_id') ? 'vendor_id' : null;
            if (! $vendorFk) {
                return response()->json(['success' => false, 'message' => 'Wallet vendor_id column missing'], 500);
            }

            $rows = DB::table($table)
                ->selectRaw("DATE($table.{$cols['dateCol']}) as day, SUM(CASE WHEN $table.{$cols['typeCol']}='credit' THEN $table.{$cols['amountCol']} ELSE 0 END) - SUM(CASE WHEN $table.{$cols['typeCol']}='debit' THEN $table.{$cols['amountCol']} ELSE 0 END) as net")
                ->where($table . '.' . $vendorFk, $vendor->id)
                ->whereRaw("DATE($table.{$cols['dateCol']}) >= ?", [$from])
                ->whereRaw("DATE($table.{$cols['dateCol']}) <= ?", [$to])
                ->groupBy(DB::raw("DATE($table.{$cols['dateCol']})"))
                ->orderBy(DB::raw("DATE($table.{$cols['dateCol']})"), 'asc')
                ->get();

            $map = [];
            foreach ($rows as $r) {
                $map[(string) $r->day] = (float) $r->net;
            }

            $data = [];
            $start = Carbon::parse($from);
            $end = Carbon::parse($to);
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $ds = $d->toDateString();
                $data[] = ['date' => $ds, 'net_earning' => (float) ($map[$ds] ?? 0)];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'range' => $range,
                    'from' => $from,
                    'to' => $to,
                    'daily' => $data,
                ],
            ]);
        }

        // Fallback: orders-derived daily sales (not necessarily profit)
        $ordersTable = $table;
        $vendorFk = Schema::hasColumn($ordersTable, 'seller_id') ? 'seller_id' : (Schema::hasColumn($ordersTable, 'vendor_id') ? 'vendor_id' : null);
        if (! $vendorFk) {
            return response()->json(['success' => false, 'message' => 'Orders vendor FK missing'], 500);
        }

        $dateCol = null;
        foreach (['order_date', 'created_at', 'billing_date', 'transaction_date', 'date'] as $c) {
            if (Schema::hasColumn($ordersTable, $c)) {
                $dateCol = $c;
                break;
            }
        }
        $amountCol = null;
        foreach (['billing_amount', 'order_amount', 'total_amount', 'grand_total', 'amount', 'net_amount'] as $c) {
            if (Schema::hasColumn($ordersTable, $c)) {
                $amountCol = $c;
                break;
            }
        }
        if (! $dateCol || ! $amountCol) {
            return response()->json(['success' => false, 'message' => 'Orders date/amount columns missing'], 500);
        }

        $rows = DB::table($ordersTable)
            ->selectRaw("DATE($ordersTable.$dateCol) as day, SUM($ordersTable.$amountCol) as total")
            ->where($ordersTable . '.' . $vendorFk, $vendor->id)
            ->whereRaw("DATE($ordersTable.$dateCol) >= ?", [$from])
            ->whereRaw("DATE($ordersTable.$dateCol) <= ?", [$to])
            ->groupBy(DB::raw("DATE($ordersTable.$dateCol)"))
            ->orderBy(DB::raw("DATE($ordersTable.$dateCol)"), 'asc')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $map[(string) $r->day] = (float) $r->total;
        }

        $data = [];
        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $ds = $d->toDateString();
            $data[] = ['date' => $ds, 'net_earning' => (float) ($map[$ds] ?? 0)];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range,
                'from' => $from,
                'to' => $to,
                'daily' => $data,
                'note' => 'Derived from orders amount (wallet table not found).',
            ],
        ]);
    }

    public function breakdown(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $range = strtolower((string) $request->query('range', 'monthly'));
        $from = null;
        $to = null;

        if ($range === 'today') {
            $from = Carbon::today()->toDateString();
            $to = Carbon::today()->toDateString();
        } elseif ($range === 'monthly') {
            $from = Carbon::now()->startOfMonth()->toDateString();
            $to = Carbon::now()->endOfMonth()->toDateString();
        } elseif ($range === 'yearly') {
            $from = Carbon::now()->startOfYear()->toDateString();
            $to = Carbon::now()->endOfYear()->toDateString();
        } elseif ($range === 'custom') {
            $request->validate(['from' => 'required|date', 'to' => 'required|date|after_or_equal:from']);
            $from = Carbon::parse($request->query('from'))->toDateString();
            $to = Carbon::parse($request->query('to'))->toDateString();
        } else {
            $request->validate(['range' => 'in:today,monthly,yearly,custom']);
        }

        if (! Schema::hasTable('vendor_wallet_transactions')) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet transactions table not available; breakdown requires vendor_wallet_transactions',
            ], 500);
        }

        $amountCol = Schema::hasColumn('vendor_wallet_transactions', 'amount') ? 'amount' : null;
        $typeCol = Schema::hasColumn('vendor_wallet_transactions', 'transaction_type') ? 'transaction_type' : null;
        $dateCol = Schema::hasColumn('vendor_wallet_transactions', 'created_at') ? 'created_at' : null;

        if (! $amountCol || ! $typeCol || ! $dateCol) {
            return response()->json(['success' => false, 'message' => 'Wallet transaction columns missing'], 500);
        }

        if (! Schema::hasColumn('vendor_wallet_transactions', 'vendor_id')) {
            return response()->json(['success' => false, 'message' => 'Wallet vendor_id column missing'], 500);
        }

        // Graph breakdown by transaction_type (credit vs debit)
        $rows = DB::table('vendor_wallet_transactions')
            ->selectRaw("$typeCol as type, SUM(CASE WHEN $typeCol='credit' THEN $amountCol ELSE 0 END) as credit_total, SUM(CASE WHEN $typeCol='debit' THEN $amountCol ELSE 0 END) as debit_total")
            ->where('vendor_id', $vendor->id)
            ->whereRaw("DATE($dateCol) >= ?", [$from])
            ->whereRaw("DATE($dateCol) <= ?", [$to])
            ->groupBy($typeCol)
            ->get();

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'type' => $r->type,
                'credit_total' => (float) $r->credit_total,
                'debit_total' => (float) $r->debit_total,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range,
                'from' => $from,
                'to' => $to,
                'breakdown' => $out,
            ],
        ]);
    }
}

