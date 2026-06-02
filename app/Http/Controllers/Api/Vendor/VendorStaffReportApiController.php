<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorStaffReportApiController extends Controller
{
    private function vendor(Request $request): ?Vendor
    {
        $vendor = $request->user();
        return $vendor instanceof Vendor ? $vendor : null;
    }

    public function staffReport(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $staff = VendorStaff::where('vendor_id', $vendor->id)->orderByDesc('performance_score')->get();

        // Ensure chart-friendly fields
        $data = $staff->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'role' => $s->role,
                'phone' => $s->phone,
                'shift_start' => $s->shift_start,
                'shift_end' => $s->shift_end,
                'performance_score' => (float) ($s->performance_score ?? 0),
                'is_online' => (bool) ($s->is_online ?? false),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => ['staff' => $data],
        ]);
    }

    public function salesByStaff(Request $request)
    {
        $vendor = $this->vendor($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $range = strtolower((string) $request->query('range', 'monthly'));
        $from = null;
        $to = null;
        $today = now();
        if ($range === 'today') {
            $from = $today->toDateString();
            $to = $today->toDateString();
        } elseif ($range === 'monthly') {
            $from = $today->copy()->startOfMonth()->toDateString();
            $to = $today->copy()->endOfMonth()->toDateString();
        } elseif ($range === 'yearly') {
            $from = $today->copy()->startOfYear()->toDateString();
            $to = $today->copy()->endOfYear()->toDateString();
        } elseif ($range === 'custom') {
            $request->validate(['from' => 'required|date', 'to' => 'required|date|after_or_equal:from']);
            $from = now()->parse($request->query('from'))->toDateString();
            $to = now()->parse($request->query('to'))->toDateString();
        } else {
            $request->validate(['range' => 'in:today,monthly,yearly,custom']);
        }

        // Best-effort: use whichever orders table exists, then group by staff_id.
        $ordersTable = Schema::hasTable('vendor_orders') ? 'vendor_orders' : (Schema::hasTable('orders') ? 'orders' : (Schema::hasTable('user_orders') ? 'user_orders' : null));
        if (! $ordersTable) {
            return response()->json(['success' => false, 'message' => 'No orders table found'], 500);
        }

        $vendorFk = Schema::hasColumn($ordersTable, 'vendor_id') ? 'vendor_id' : (Schema::hasColumn($ordersTable, 'seller_id') ? 'seller_id' : null);
        if (! $vendorFk) {
            return response()->json(['success' => false, 'message' => 'Orders vendor FK missing'], 500);
        }

        $staffFk = null;
        foreach (['staff_id', 'vendor_staff_id', 'staffId'] as $c) {
            if (Schema::hasColumn($ordersTable, $c)) {
                $staffFk = $c;
                break;
            }
        }
        if (! $staffFk) {
            return response()->json(['success' => false, 'message' => 'Orders staff FK missing; cannot compute sales by staff'], 500);
        }

        $dateCol = null;
        foreach (['created_at', 'order_date', 'billing_date', 'transaction_date', 'date'] as $c) {
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
            ->selectRaw("$staffFk as staff_id, SUM($amountCol) as total_sales, COUNT(*) as orders_count")
            ->where($vendorFk, $vendor->id)
            ->whereRaw("DATE($dateCol) >= ?", [$from])
            ->whereRaw("DATE($dateCol) <= ?", [$to])
            ->groupBy($staffFk)
            ->orderByDesc('total_sales')
            ->get();

        // Attach staff names
        $staffMap = VendorStaff::where('vendor_id', $vendor->id)->get()->keyBy('id');

        $out = $rows->map(function ($r) use ($staffMap) {
            $s = $staffMap->get((int) $r->staff_id);
            return [
                'staff_id' => (int) $r->staff_id,
                'staff_name' => $s?->name,
                'orders_count' => (int) $r->orders_count,
                'total_sales' => (float) $r->total_sales,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range,
                'from' => $from,
                'to' => $to,
                'sales_by_staff' => $out,
            ],
        ]);
    }
}

