<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorGlobalDistribution;
use App\Models\VendorGlobalVendorDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VendorGlobalFundController extends Controller
{
    public function index()
    {
        return view('admin.vendor-global-fund.index');
    }

    public function data(Request $request)
    {
        $distributionId = $request->input('distribution_id');
        if (! $distributionId) {
            $latest = VendorGlobalDistribution::orderBy('id', 'desc')->first();
            $distributionId = $latest?->id;
        }

        $allocations = [];
        if ($distributionId) {
            $allocations = VendorGlobalVendorDistribution::where('distribution_id', $distributionId)
                ->pluck('amount', 'vendor_id')
                ->toArray();
        }

        $query = Vendor::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('vendor_id', fn ($v) => $v->id)
            ->addColumn('vendor_name', function ($v) {
                return $v->business_name ?? $v->contact_person ?? '';
            })
            ->addColumn('mobile_no', function ($v) {
                return $v->mobile_no ?? $v->contact_mobile_no ?? '';
            })
            ->addColumn('fund', function ($v) use ($allocations) {
                $amt = $allocations[$v->id] ?? 0;

                return number_format($amt, 2);
            })
            ->make(true);
    }

    public function distribute(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = floatval($request->input('amount'));
        $vendors = Vendor::select('id')->get();
        $count = $vendors->count();
        if ($count === 0) {
            return response()->json(['success' => false, 'message' => 'No vendors found to distribute.'], 422);
        }

        return DB::transaction(function () use ($amount, $vendors, $count) {
            $distribution = VendorGlobalDistribution::create([
                'total_amount' => $amount,
                'vendor_count' => $count,
                'created_by_user_id' => Auth::id(),
            ]);

            // Equal split with remainder adjustment on last vendor
            $perVendor = floor(($amount / $count) * 100) / 100; // two decimals down
            $allocated = 0.0;
            foreach ($vendors as $idx => $vendor) {
                $isLast = ($idx === $count - 1);
                $allocAmount = $isLast ? round($amount - $allocated, 2) : $perVendor;
                VendorGlobalVendorDistribution::create([
                    'distribution_id' => $distribution->id,
                    'vendor_id' => $vendor->id,
                    'amount' => $allocAmount,
                ]);
                $allocated += $allocAmount;
            }

            return response()->json([
                'success' => true,
                'distribution_id' => $distribution->id,
                'summary' => [
                    'total_amount' => $amount,
                    'vendor_count' => $count,
                    'per_vendor' => $perVendor,
                ],
            ]);
        });
    }
}
