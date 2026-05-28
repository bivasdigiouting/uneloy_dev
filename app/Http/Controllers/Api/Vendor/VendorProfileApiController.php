<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VendorProfileApiController extends Controller
{
    private function getVendorFromAuth(Request $request): ?Vendor
    {
        $vendor = $request->user();
        if (! $vendor instanceof Vendor) {
            return null;
        }
        return $vendor;
    }

    public function dashboard(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $totalProducts = \App\Models\Product::where('vendor_id', $vendor->id)->count();

        $ordersTable = null;
        try {
            if (Schema::hasTable('vendor_orders')) {
                $ordersTable = 'vendor_orders';
            } elseif (Schema::hasTable('orders')) {
                $ordersTable = 'orders';
            } elseif (Schema::hasTable('user_orders')) {
                $ordersTable = 'user_orders';
            }
        } catch (\Throwable $e) {
            $ordersTable = null;
        }

        $totalOrders = 0;
        $pendingOrders = 0;

        if ($ordersTable) {
            $statusCol = 'status';
            if (! Schema::hasColumn($ordersTable, $statusCol)) {
                if (Schema::hasColumn($ordersTable, 'order_status')) {
                    $statusCol = 'order_status';
                } else {
                    $statusCol = null;
                }
            }

            $base = \Illuminate\Support\Facades\DB::table($ordersTable);
            if (Schema::hasColumn($ordersTable, 'seller_id')) {
                $base->where('seller_id', $vendor->id);
            } elseif (Schema::hasColumn($ordersTable, 'vendor_id')) {
                $base->where('vendor_id', $vendor->id);
            }

            $totalOrders = (int) ($base->count() ?? 0);

            if ($statusCol) {
                $pendingOrders = (int) (\Illuminate\Support\Facades\DB::table($ordersTable)
                    ->when(Schema::hasColumn($ordersTable, 'seller_id'), function ($q) use ($vendor) {
                        $q->where('seller_id', $vendor->id);
                    })
                    ->when(Schema::hasColumn($ordersTable, 'vendor_id'), function ($q) use ($vendor) {
                        $q->where('vendor_id', $vendor->id);
                    })
                    ->whereIn($statusCol, ['Pending', 'pending', 'Awaiting', 'Processing', 'processing', 'Shipped'])
                    ->count());
            }
        }

        $earnings = 0.0;
        if (Schema::hasTable('vendor_wallet_transactions')) {
            $earnings = (float) (VendorWalletTransaction::where('vendor_id', $vendor->id)
                ->selectRaw("SUM(CASE WHEN transaction_type = 'credit' THEN amount ELSE 0 END) - SUM(CASE WHEN transaction_type = 'debit' THEN amount ELSE 0 END) as net")
                ->value('net') ?? 0);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'totalProducts' => $totalProducts,
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'earnings' => $earnings,
                'vendor' => $vendor,
            ],
        ]);
    }

    public function profile(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'vendor' => $vendor,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'business_full_address' => 'nullable|string|max:500',
        ]);

        $vendor->update($request->only([
            'first_name',
            'last_name',
            'business_name',
            'mobile_no',
            'business_full_address',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => ['vendor' => $vendor->fresh()],
        ]);
    }

    public function changePassword(Request $request)
    {
        $vendor = $this->getVendorFromAuth($request);
        if (! $vendor) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! \Illuminate\Support\Facades\Hash::check($request->current_password, $vendor->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The current password is incorrect.',
            ], 422);
        }

        $vendor->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }
}

