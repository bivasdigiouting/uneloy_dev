<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorTransactionsApiController extends Controller
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

    private function resolveOrderIdColumn(string $table): ?string
    {
        if (Schema::hasColumn($table, 'id')) {
            return 'id';
        }
        if (Schema::hasColumn($table, 'order_id')) {
            return 'order_id';
        }
        return null;
    }

    private function resolveCreatedAtColumn(string $table): ?string
    {
        foreach (['created_at', 'order_date', 'billing_date', 'transaction_date', 'date'] as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }
        return null;
    }

    public function list(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFk = $this->resolveVendorFkColumn($ordersTable);
        $idCol = $this->resolveOrderIdColumn($ordersTable);
        $createdAt = $this->resolveCreatedAtColumn($ordersTable);

        if (! $vendorFk || ! $idCol || ! $createdAt) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $page = max(1, (int) $request->query('page', 1));
        $perPage = (int) $request->query('per_page', 20);
        if ($perPage < 1) {
            $perPage = 20;
        }

        $from = $request->query('from');
        $to = $request->query('to');

        $q = DB::table($ordersTable)
            ->where($ordersTable . '.' . $vendorFk, $vendor->id);

        if ($from) {
            $q->whereRaw('DATE(' . $ordersTable . '.' . $createdAt . ') >= ?', [Carbon::parse($from)->toDateString()]);
        }
        if ($to) {
            $q->whereRaw('DATE(' . $ordersTable . '.' . $createdAt . ') <= ?', [Carbon::parse($to)->toDateString()]);
        }

        $q->orderBy($ordersTable . '.' . $createdAt, 'desc');

        $total = (int) $q->count();
        $rows = $q
            ->forPage($page, $perPage)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'transactions' => $rows,
            ],
        ]);
    }

    public function details(Request $request, int $id)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFk = $this->resolveVendorFkColumn($ordersTable);
        $idCol = $this->resolveOrderIdColumn($ordersTable);

        if (! $vendorFk || ! $idCol) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $tx = DB::table($ordersTable)
            ->where($ordersTable . '.' . $vendorFk, $vendor->id)
            ->where($ordersTable . '.' . $idCol, $id)
            ->first();

        if (! $tx) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => ['transaction' => $tx],
        ]);
    }

    public function statements(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $ordersTable = $this->resolveOrderTable();
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFk = $this->resolveVendorFkColumn($ordersTable);
        if (! $vendorFk) {
            return response()->json(['success' => false, 'message' => 'Required order columns missing'], 500);
        }

        $dateCol = $this->resolveCreatedAtColumn($ordersTable);
        if (! $dateCol) {
            return response()->json(['success' => false, 'message' => 'Order date column missing'], 500);
        }

        // Amount column best-effort
        $amountCol = null;
        foreach (['billing_amount', 'order_amount', 'total_amount', 'grand_total', 'amount', 'net_amount'] as $c) {
            if (Schema::hasColumn($ordersTable, $c)) {
                $amountCol = $c;
                break;
            }
        }

        $page = max(1, (int) $request->query('page', 1));
        $perPage = (int) $request->query('per_page', 20);
        if ($perPage < 1) {
            $perPage = 20;
        }

        $from = $request->query('from');
        $to = $request->query('to');

        $q = DB::table($ordersTable)
            ->where($ordersTable . '.' . $vendorFk, $vendor->id);

        if ($from) {
            $q->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') >= ?', [Carbon::parse($from)->toDateString()]);
        }
        if ($to) {
            $q->whereRaw('DATE(' . $ordersTable . '.' . $dateCol . ') <= ?', [Carbon::parse($to)->toDateString()]);
        }

        $q->orderBy($ordersTable . '.' . $dateCol, 'desc');

        $total = (int) $q->count();
        $rows = $q->forPage($page, $perPage)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'range' => [
                    'from' => $from,
                    'to' => $to,
                ],
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'statements' => $rows,
                'amount_column' => $amountCol,
            ],
        ]);
    }
}

