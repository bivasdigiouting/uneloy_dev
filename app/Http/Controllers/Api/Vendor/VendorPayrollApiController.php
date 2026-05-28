<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorPayroll;
use App\Models\VendorStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorPayrollApiController extends Controller
{
    private function vendorId(Request $request): ?int
    {
        $vendor = $request->user();
        return $vendor ? (int) $vendor->id : null;
    }

    public function index(Request $request)
    {
        $vendorId = $this->vendorId($request);
        if (! $vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $currentMonth = now()->startOfMonth();

        $vendorStaffs = VendorStaff::where('vendor_id', $vendorId)->get();
        $payrolls = VendorPayroll::where('vendor_id', $vendorId)
            ->where('month_year', $currentMonth->format('Y-m-d'))
            ->get();

        // Ensure payroll rows exist for each staff for current month
        foreach ($vendorStaffs as $staff) {
            if (! $payrolls->contains('vendor_staff_id', $staff->id)) {
                $newPayroll = VendorPayroll::create([
                    'vendor_id' => $vendorId,
                    'vendor_staff_id' => $staff->id,
                    'month_year' => $currentMonth->format('Y-m-d'),
                    'base_salary' => $staff->base_salary,
                    'incentive' => 0,
                    'status' => 'pending',
                ]);
                $payrolls->push($newPayroll);
            }
        }

        $totalBasePaid = VendorPayroll::where('vendor_id', $vendorId)->where('status', 'paid')->sum('base_salary');
        $totalIncentivePaid = VendorPayroll::where('vendor_id', $vendorId)->where('status', 'paid')->sum('incentive');
        $totalDisbursement = $totalBasePaid + $totalIncentivePaid;

        $pendingQueue = $payrolls->where('status', 'pending')->values();

        return response()->json([
            'success' => true,
            'data' => [
                'currentMonth' => $currentMonth->toDateString(),
                'payrolls' => $payrolls,
                'pendingQueue' => $pendingQueue,
                'totalDisbursement' => (float) $totalDisbursement,
                'totalBasePaid' => (float) $totalBasePaid,
                'totalIncentivePaid' => (float) $totalIncentivePaid,
            ],
        ]);
    }

    public function process(Request $request)
    {
        $vendorId = $this->vendorId($request);
        if (! $vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $currentMonth = now()->startOfMonth()->format('Y-m-d');

        VendorPayroll::where('vendor_id', $vendorId)
            ->where('month_year', $currentMonth)
            ->where('status', 'pending')
            ->update(['status' => 'paid']);

        return response()->json([
            'success' => true,
            'message' => 'Payroll processed successfully. All pending staff have been paid.',
        ]);
    }
}

