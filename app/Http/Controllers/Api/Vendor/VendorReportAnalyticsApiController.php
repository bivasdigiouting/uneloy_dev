<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorReportAnalyticsApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    private function resolveOrderTable(): ?string
    {
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

    private function resolveVendorFkColumn(string $table): ?string
    {
        foreach (['seller_id', 'vendor_id', 'sellerId', 'vendorId'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }
        return null;
    }

    private function resolveOrderDateColumn(string $table): ?string
    {
        foreach (['order_date', 'created_at', 'date', 'transaction_date', 'billing_date'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }
        return null;
    }

    private function resolveSalesAmountColumn(string $table): ?string
    {
        // totals can be stored at order-level
        foreach (['billing_amount', 'order_amount', 'total_amount', 'grand_total', 'amount', 'total', 'net_amount', 'sales_amount'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }
        return null;
    }

    private function resolveStatusColumn(string $table): ?string
    {
        foreach (['status', 'order_status', 'payment_status'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }
        return null;
    }

    private function applyPaidFilter($q, string $table, ?string $statusCol): void
    {
        if (! $statusCol) {
            return;
        }

        $paidCandidates = ['paid', 'success', 'completed', 'confirmed'];
        $q->whereRaw(
            'LOWER(' . $table . '.' . $statusCol . ') IN (' . implode(',', array_fill(0, count($paidCandidates), '?')) . ')',
            array_map(fn ($v) => strtolower($v), $paidCandidates)
        );
    }

    private function resolveProfitExpression(string $table, ?string $salesAmountCol): ?array
    {
        // Best-effort profit computation:
        // 1) direct profit/net_profit column if exists
        foreach (['profit', 'net_profit', 'total_profit', 'earned_profit'] as $col) {
            if ($col && Schema::hasColumn($table, $col)) {
                return ['type' => 'direct', 'column' => $col];
            }
        }

        // 2) common cost vs sales pattern
        foreach (['total_cost', 'cost_amount', 'order_cost', 'amount_cost', 'net_cost'] as $costCol) {
            if ($costCol && Schema::hasColumn($table, $costCol) && $salesAmountCol) {
                return ['type' => 'diff', 'salesCol' => $salesAmountCol, 'costCol' => $costCol];
            }
        }

        // 3) margin/percent based pattern (if exists)
        foreach (['margin_amount', 'margin'] as $marginCol) {
            if ($marginCol && Schema::hasColumn($table, $marginCol) && $salesAmountCol) {
                return ['type' => 'margin_as_profit', 'column' => $marginCol, 'salesCol' => $salesAmountCol];
            }
        }

        return null;
    }

    private function parseDate(string $date): Carbon
    {
        return Carbon::parse($date)->startOfDay();
    }

    private function parseMonth(string $month): Carbon
    {
        // expects YYYY-MM
        return Carbon::parse($month . '-01')->startOfMonth();
    }

    public function daily(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $dateStr = (string) $request->query('date');
        if ($dateStr === '') {
            return response()->json(['success' => false, 'message' => 'date query param is required (YYYY-MM-DD)'], 422);
        }

        $day = $this->parseDate($dateStr);
        $from = $day->toDateString();
        $to = $day->toDateString();

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFkCol = $this->resolveVendorFkColumn($ordersTable);
        $dateCol = $this->resolveOrderDateColumn($ordersTable);
        $salesCol = $this->resolveSalesAmountColumn($ordersTable);
        $statusCol = $this->resolveStatusColumn($ordersTable);

        if (! $vendorFkCol || ! $dateCol || ! $salesCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $profitSpec = $this->resolveProfitExpression($ordersTable, $salesCol);

        $q = DB::table($ordersTable)
            ->where($ordersTable . '.' . $vendorFkCol, $vendor->id)
            ->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') >= ?', [$from])
            ->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') <= ?', [$to]);

        $this->applyPaidFilter($q, $ordersTable, $statusCol);

        $totalSales = (float) ($q->clone()->sum($salesCol) ?? 0);
        $transactions = (int) ($q->clone()->count() ?? 0);

        $profitValue = null;
        if ($profitSpec) {
            if ($profitSpec['type'] === 'direct') {
                $profitValue = (float) ($q->clone()->sum($profitSpec['column']) ?? 0);
            } elseif ($profitSpec['type'] === 'diff') {
                $profitValue = (float) $q
                    ->clone()
                    ->selectRaw('SUM(' . $profitSpec['salesCol'] . ' - ' . $profitSpec['costCol'] . ') as p')
                    ->value('p');
            } elseif ($profitSpec['type'] === 'margin_as_profit') {
                // If margin is percent, profit calc would require more info. Best-effort treat margin_amount as profit.
                $col = $profitSpec['column'];
                $profitValue = (float) ($q->clone()->sum($col) ?? 0);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'filters' => [
                    'date' => $day->toDateString(),
                ],
                'totals' => [
                    'total_sales' => $totalSales,
                    'profit' => $profitValue,
                    'transactions' => $transactions,
                    'currency' => 'INR',
                ],
                'chart' => [
                    // single datapoint for selected day
                    ['date' => $day->toDateString(), 'total_sale' => $totalSales, 'profit' => $profitValue],
                ],
            ],
        ]);
    }

    public function monthly(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $monthStr = (string) $request->query('month');
        if ($monthStr === '') {
            return response()->json(['success' => false, 'message' => 'month query param is required (YYYY-MM)'], 422);
        }

        $month = $this->parseMonth($monthStr);
        $from = $month->toDateString();
        $to = $month->copy()->endOfMonth()->toDateString();

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFkCol = $this->resolveVendorFkColumn($ordersTable);
        $dateCol = $this->resolveOrderDateColumn($ordersTable);
        $salesCol = $this->resolveSalesAmountColumn($ordersTable);
        $statusCol = $this->resolveStatusColumn($ordersTable);

        if (! $vendorFkCol || ! $dateCol || ! $salesCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $profitSpec = $this->resolveProfitExpression($ordersTable, $salesCol);

        $base = DB::table($ordersTable)
            ->where($ordersTable . '.' . $vendorFkCol, $vendor->id)
            ->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') >= ?', [$from])
            ->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') <= ?', [$to]);

        $this->applyPaidFilter($base, $ordersTable, $statusCol);

        $dailySales = $base
            ->clone()
            ->selectRaw('DATE(' . $ordersTable . '.' . $dateCol . ') as d, SUM(' . $salesCol . ') as total_sale')
            ->groupBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'))
            ->orderBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'), 'asc')
            ->get();

        $salesMap = [];
        foreach ($dailySales as $r) {
            $salesMap[(string) $r->d] = (float) $r->total_sale;
        }

        $profitMap = [];
        if ($profitSpec) {
            if ($profitSpec['type'] === 'direct') {
                $rows = $base
                    ->clone()
                    ->selectRaw('DATE(' . $ordersTable . '.' . $dateCol . ') as d, SUM(' . $profitSpec['column'] . ') as profit')
                    ->groupBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'))
                    ->orderBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'), 'asc')
                    ->get();

                foreach ($rows as $r) {
                    $profitMap[(string) $r->d] = (float) $r->profit;
                }
            } elseif ($profitSpec['type'] === 'diff') {
                $rows = $base
                    ->clone()
                    ->selectRaw('DATE(' . $ordersTable . '.' . $dateCol . ') as d, SUM(' . $profitSpec['salesCol'] . ' - ' . $profitSpec['costCol'] . ') as profit')
                    ->groupBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'))
                    ->orderBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'), 'asc')
                    ->get();

                foreach ($rows as $r) {
                    $profitMap[(string) $r->d] = (float) $r->profit;
                }
            } else {
                // fallback: not computed per-day
            }
        }

        $transactions = (int) ($base->clone()->count() ?? 0);
        $totalSales = (float) ($base->clone()->sum($salesCol) ?? 0);
        $totalProfit = null;
        if ($profitSpec) {
            if ($profitSpec['type'] === 'direct') {
                $totalProfit = (float) ($base->clone()->sum($profitSpec['column']) ?? 0);
            } elseif ($profitSpec['type'] === 'diff') {
                $totalProfit = (float) $base
                    ->clone()
                    ->selectRaw('SUM(' . $profitSpec['salesCol'] . ' - ' . $profitSpec['costCol'] . ') as p')
                    ->value('p');
            }
        }

        $chart = [];
        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $ds = $d->toDateString();
            $chart[] = [
                'date' => $ds,
                'total_sale' => (float) ($salesMap[$ds] ?? 0),
                'profit' => $profitMap[$ds] ?? null,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'filters' => [
                    'month' => $month->format('Y-m'),
                    'from' => $from,
                    'to' => $to,
                ],
                'totals' => [
                    'total_sales' => $totalSales,
                    'profit' => $totalProfit,
                    'transactions' => $transactions,
                    'currency' => 'INR',
                ],
                'chart' => $chart,
            ],
        ]);
    }

    public function yearly(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $yearStr = (string) $request->query('year');
        if ($yearStr === '' || !ctype_digit($yearStr)) {
            return response()->json(['success' => false, 'message' => 'year query param is required (YYYY)'], 422);
        }

        $year = Carbon::parse($yearStr . '-01-01');
        $from = $year->copy()->startOfYear()->toDateString();
        $to = $year->copy()->endOfYear()->toDateString();

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFkCol = $this->resolveVendorFkColumn($ordersTable);
        $dateCol = $this->resolveOrderDateColumn($ordersTable);
        $salesCol = $this->resolveSalesAmountColumn($ordersTable);
        $statusCol = $this->resolveStatusColumn($ordersTable);

        if (! $vendorFkCol || ! $dateCol || ! $salesCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $profitSpec = $this->resolveProfitExpression($ordersTable, $salesCol);

        $base = DB::table($ordersTable)
            ->where($ordersTable . '.' . $vendorFkCol, $vendor->id)
            ->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') >= ?', [$from])
            ->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') <= ?', [$to]);

        $this->applyPaidFilter($base, $ordersTable, $statusCol);

        $monthlySales = $base
            ->clone()
            ->selectRaw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m') as ym, SUM({$ordersTable}.{$salesCol}) as total_sale")
            ->groupBy(DB::raw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m')"), 'asc')
            ->get();

        $salesMap = [];
        foreach ($monthlySales as $r) {
            $salesMap[(string) $r->ym] = (float) $r->total_sale;
        }

        $profitMap = [];
        if ($profitSpec) {
            if ($profitSpec['type'] === 'direct') {
                $rows = $base
                    ->clone()
                    ->selectRaw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m') as ym, SUM({$ordersTable}.{$profitSpec['column']}) as profit")
                    ->groupBy(DB::raw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m')"))
                    ->orderBy(DB::raw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m')"), 'asc')
                    ->get();

                foreach ($rows as $r) {
                    $profitMap[(string) $r->ym] = (float) $r->profit;
                }
            } elseif ($profitSpec['type'] === 'diff') {
                $rows = $base
                    ->clone()
                    ->selectRaw(
                        "DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m') as ym, SUM({$profitSpec['salesCol']} - {$profitSpec['costCol']}) as profit"
                    )
                    ->groupBy(DB::raw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m')"))
                    ->orderBy(DB::raw("DATE_FORMAT({$ordersTable}.{$dateCol}, '%Y-%m')"), 'asc')
                    ->get();

                foreach ($rows as $r) {
                    $profitMap[(string) $r->ym] = (float) $r->profit;
                }
            }
        }

        $transactions = (int) ($base->clone()->count() ?? 0);
        $totalSales = (float) ($base->clone()->sum($salesCol) ?? 0);
        $totalProfit = null;
        if ($profitSpec) {
            if ($profitSpec['type'] === 'direct') {
                $totalProfit = (float) ($base->clone()->sum($profitSpec['column']) ?? 0);
            } elseif ($profitSpec['type'] === 'diff') {
                $totalProfit = (float) $base
                    ->clone()
                    ->selectRaw('SUM(' . $profitSpec['salesCol'] . ' - ' . $profitSpec['costCol'] . ') as p')
                    ->value('p');
            }
        }

        $chart = [];
        for ($m = 1; $m <= 12; $m++) {
            $ym = $year->copy()->month($m)->format('Y-m');
            $chart[] = [
                'month' => $ym,
                'total_sale' => (float) ($salesMap[$ym] ?? 0),
                'profit' => $profitMap[$ym] ?? null,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'filters' => [
                    'year' => (int) $yearStr,
                    'from' => $from,
                    'to' => $to,
                ],
                'totals' => [
                    'total_sales' => $totalSales,
                    'profit' => $totalProfit,
                    'transactions' => $transactions,
                    'currency' => 'INR',
                ],
                'chart' => $chart,
            ],
        ]);
    }

    public function inventory(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        if (! Schema::hasTable('products')) {
            return response()->json(['success' => false, 'message' => 'products table not found'], 500);
        }

        $q = DB::table('products')->where('vendor_id', $vendor->id);

        $totalProducts = (int) $q->clone()->count();

        $totalStock = 0;
        if (Schema::hasColumn('products', 'stock')) {
            $totalStock = (int) ($q->clone()->sum('stock') ?? 0);
        }

        $lowStockCount = null;
        if (Schema::hasColumn('products', 'stock')) {
            $lowStockCount = (int) ($q->clone()->where('stock', '>', 0)->where('stock', '<=', 5)->count() ?? 0);
        }

        $topProducts = [];
        if (Schema::hasColumn('products', 'stock')) {
            $topProducts = $q
                ->clone()
                ->select(['id', 'name', 'category', 'brand', 'stock', 'price', 'distributor_price'])
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => (int) $p->id,
                        'name' => $p->name ?? '-',
                        'category' => $p->category ?? null,
                        'brand' => $p->brand ?? null,
                        'stock' => (int) $p->stock,
                        'price' => property_exists($p, 'price') ? (float) $p->price : null,
                        'distributor_price' => property_exists($p, 'distributor_price') ? (float) $p->distributor_price : null,
                    ];
                })
                ->sortByDesc('stock')
                ->take(10)
                ->values()
                ->all();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'totals' => [
                    'total_products' => $totalProducts,
                    'total_items_quantity' => $totalStock,
                    'low_stock_count' => $lowStockCount,
                ],
                'top_products_by_stock' => $topProducts,
            ],
        ]);
    }
}

