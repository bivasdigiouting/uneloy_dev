<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorSalesApiController extends Controller
{
    private function getVendorFromAuth(Request $request): ?Vendor
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

    private function resolveOrderDateColumn(string $table): ?string
    {
        $candidates = ['order_date', 'created_at', 'date', 'transaction_date', 'billing_date'];
        foreach ($candidates as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }

        return null;
    }

    private function resolveVendorFkColumn(string $table): ?string
    {
        if (Schema::hasColumn($table, 'seller_id')) {
            return 'seller_id';
        }
        if (Schema::hasColumn($table, 'vendor_id')) {
            return 'vendor_id';
        }
        if (Schema::hasColumn($table, 'sellerId')) {
            return 'sellerId';
        }
        if (Schema::hasColumn($table, 'vendorId')) {
            return 'vendorId';
        }

        return null;
    }

    private function resolveAmountColumn(string $table): ?string
    {
        // Totals can be stored at order-level
        $candidates = ['billing_amount', 'order_amount', 'total_amount', 'grand_total', 'amount', 'total', 'net_amount'];
        foreach ($candidates as $col) {
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

    private function statusPaidPredicate(string $statusValue): bool
    {
        $v = strtolower(trim((string) $statusValue));
        return in_array($v, [
            'paid',
            'success',
            'successful',
            'completed',
            'complete',
            'confirmed',
            'paid_success',
            'successfull',
            'paidad',
            'paidsuccess',
            'paid/completed',
        ], true);
    }

    private function applyDateRange($query, string $table, string $dateCol, string $from, string $to)
    {
        // Use date() to keep compatibility with timestamp columns.
        return $query->whereRaw("DATE($table.$dateCol) >= ?", [$from])
            ->whereRaw("DATE($table.$dateCol) <= ?", [$to]);
    }

    private function parseRange(Request $request): array
    {
        $range = strtolower((string) $request->query('range', 'today'));

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
            $request->validate([
                'from' => 'required|date',
                'to' => 'required|date|after_or_equal:from',
            ]);
            $from = Carbon::parse($request->query('from'))->toDateString();
            $to = Carbon::parse($request->query('to'))->toDateString();
        } else {
            $request->validate([
                'range' => 'in:today,monthly,yearly,custom',
            ]);
        }

        return compact('range', 'from', 'to');
    }

    private function resolveItemTable(): ?string
    {
        // Common naming patterns in e-commerce.
        $candidates = [
            'order_items',
            'vendor_order_items',
            'user_order_items',
            'order_details',
            'vendor_order_details',
            'user_order_details',
            'cart_items',
            'order_line_items',
        ];

        foreach ($candidates as $t) {
            if (Schema::hasTable($t)) {
                return $t;
            }
        }

        return null;
    }

    private function resolveOrderIdColumnInItems(string $itemsTable, string $ordersTable): ?string
    {
        // Look for possible foreign keys referencing order.
        foreach (['order_id', 'vendor_order_id', 'user_order_id', 'ecard_sale_id', 'sale_id', 'ecardsale_id'] as $col) {
            if (Schema::hasColumn($itemsTable, $col)) {
                return $col;
            }
        }

        // If FK is named after ordersTable
        foreach ([''.strtolower($ordersTable)."_id", 'id_'.$ordersTable] as $col) {
            if (Schema::hasColumn($itemsTable, $col)) {
                return $col;
            }
        }

        return null;
    }

    private function resolveProductIdColumnInItems(string $itemsTable): ?string
    {
        foreach (['product_id', 'item_product_id', 'pid'] as $col) {
            if (Schema::hasColumn($itemsTable, $col)) {
                return $col;
            }
        }
        return null;
    }

    private function resolveQuantityColumnInItems(string $itemsTable): ?string
    {
        foreach (['quantity', 'qty', 'total_qty'] as $col) {
            if (Schema::hasColumn($itemsTable, $col)) {
                return $col;
            }
        }
        return null;
    }

    private function resolveLineTotalColumnInItems(string $itemsTable): ?string
    {
        foreach (['total_amount', 'line_total', 'line_total_amount', 'amount', 'price', 'sub_total', 'subtotal'] as $col) {
            if (Schema::hasColumn($itemsTable, $col)) {
                return $col;
            }
        }
        return null;
    }

    public function summary(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $range = $this->parseRange($request);

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $dateCol = $this->resolveOrderDateColumn($ordersTable);
        $vendorFkCol = $this->resolveVendorFkColumn($ordersTable);
        $amountCol = $this->resolveAmountColumn($ordersTable);
        $statusCol = $this->resolveStatusColumn($ordersTable);

        if (! $dateCol || ! $vendorFkCol || ! $amountCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $q = DB::table($ordersTable)->where($ordersTable . '.' . $vendorFkCol, $vendor->id);
        $q = $this->applyDateRange($q, $ordersTable, $dateCol, $range['from'], $range['to']);

        // Best-effort paid filter: only if we can inspect typical paid statuses.
        if ($statusCol) {
            $paidCandidates = ['paid', 'success', 'completed', 'confirmed'];
            $q->whereRaw('LOWER(' . $statusCol . ') IN (' . implode(',', array_fill(0, count($paidCandidates), '?')) . ')', array_map(fn ($v) => strtolower($v), $paidCandidates));
        }

        $totalSale = (float) ($q->sum($amountCol) ?? 0);

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range['range'],
                'from' => $range['from'],
                'to' => $range['to'],
                'total_sale' => $totalSale,
                'currency' => 'INR',
            ],
        ]);
    }

    public function breakdown(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $range = $this->parseRange($request);

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $dateCol = $this->resolveOrderDateColumn($ordersTable);
        $vendorFkCol = $this->resolveVendorFkColumn($ordersTable);
        $statusCol = $this->resolveStatusColumn($ordersTable);
        $amountCol = $this->resolveAmountColumn($ordersTable);

        if (! $dateCol || ! $vendorFkCol || ! $amountCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $q = DB::table($ordersTable)
            ->selectRaw('DATE(' . $ordersTable . '.' . $dateCol . ') as sale_date, SUM(' . $ordersTable . '.' . $amountCol . ') as sale_total')
            ->where($ordersTable . '.' . $vendorFkCol, $vendor->id);

        $q = $this->applyDateRange($q, $ordersTable, $dateCol, $range['from'], $range['to']);

        if ($statusCol) {
            $paidCandidates = ['paid', 'success', 'completed', 'confirmed'];
            $q->whereRaw('LOWER(' . $ordersTable . '.' . $statusCol . ') IN (' . implode(',', array_fill(0, count($paidCandidates), '?')) . ')', array_map(fn ($v) => strtolower($v), $paidCandidates));
        }

        $rows = $q->groupBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'))
            ->orderBy(DB::raw('DATE(' . $ordersTable . '.' . $dateCol . ')'), 'asc')
            ->get();

        // Ensure full date coverage in range (fill gaps with 0)
        $start = Carbon::parse($range['from']);
        $end = Carbon::parse($range['to']);
        $map = [];
        foreach ($rows as $r) {
            $map[(string) $r->sale_date] = (float) $r->sale_total;
        }

        $data = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $ds = $d->toDateString();
            $data[] = [
                'date' => $ds,
                'total_sale' => (float) ($map[$ds] ?? 0),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range['range'],
                'from' => $range['from'],
                'to' => $range['to'],
                'sale_breakdown' => $data,
            ],
        ]);
    }

    public function topProducts(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $range = $this->parseRange($request);
        $limit = (int) $request->query('limit', 10);
        if ($limit < 1) {
            $limit = 10;
        }

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $dateCol = $this->resolveOrderDateColumn($ordersTable);
        $vendorFkCol = $this->resolveVendorFkColumn($ordersTable);
        $statusCol = $this->resolveStatusColumn($ordersTable);
        $amountCol = $this->resolveAmountColumn($ordersTable);

        if (! $dateCol || ! $vendorFkCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $itemsTable = $this->resolveItemTable();

        if (! $itemsTable) {
            return response()->json([
                'success' => false,
                'message' => 'No order-items table found. top-products requires item-level table.',
            ], 500);
        }

        $orderIdInItems = $this->resolveOrderIdColumnInItems($itemsTable, $ordersTable);
        $productIdInItems = $this->resolveProductIdColumnInItems($itemsTable);
        $qtyCol = $this->resolveQuantityColumnInItems($itemsTable);
        $lineTotalCol = $this->resolveLineTotalColumnInItems($itemsTable);

        if (! $orderIdInItems || ! $productIdInItems || ! $qtyCol || ! $lineTotalCol) {
            return response()->json([
                'success' => false,
                'message' => 'Order-items table columns missing (order fk, product_id, quantity, line total).',
            ], 500);
        }

        // Join orders->items; also join products if available.
        $productsTable = Schema::hasTable('products') ? 'products' : null;
        $productNameCol = $productsTable && Schema::hasColumn($productsTable, 'name') ? 'name' : null;

        $ordersIdCol = Schema::hasColumn($ordersTable, 'id') ? 'id' : null;
        if (! $ordersIdCol) {
            return response()->json(['success' => false, 'message' => 'Orders table id column missing'], 500);
        }

        $selects = [
            $itemsTable . '.' . $productIdInItems . ' as product_id',
            DB::raw('SUM(' . $itemsTable . '.' . $qtyCol . ') as total_qty'),
            DB::raw('SUM(' . $itemsTable . '.' . $lineTotalCol . ') as total_sale'),
        ];

        if ($productsTable && $productNameCol) {
            $selects[] = DB::raw($productsTable . '.' . $productNameCol . ' as product_name');
        }

        $q = DB::table($ordersTable)
            ->join($itemsTable, $ordersTable . '.' . $ordersIdCol, '=', $itemsTable . '.' . $orderIdInItems)
            ->select($selects)
            ->where($ordersTable . '.' . $vendorFkCol, $vendor->id);

        $q = $this->applyDateRange($q, $ordersTable, $dateCol, $range['from'], $range['to']);

        if ($statusCol) {
            $paidCandidates = ['paid', 'success', 'completed', 'confirmed'];
            $q->whereRaw('LOWER(' . $ordersTable . '.' . $statusCol . ') IN (' . implode(',', array_fill(0, count($paidCandidates), '?')) . ')', array_map(fn ($v) => strtolower($v), $paidCandidates));
        }

        if ($productsTable) {
            if (Schema::hasColumn($productsTable, 'id')) {
                $q->leftJoin($productsTable, $productsTable . '.id', '=', $itemsTable . '.' . $productIdInItems);
            }
        }

        $groupBy = [$itemsTable . '.' . $productIdInItems];
        if ($productsTable && $productNameCol) {
            $groupBy[] = $productsTable . '.' . $productNameCol;
        }

        $rows = $q->groupBy($groupBy)
            ->orderByDesc('total_sale')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range['range'],
                'from' => $range['from'],
                'to' => $range['to'],
                'top_products' => $rows,
            ],
        ]);
    }
}

