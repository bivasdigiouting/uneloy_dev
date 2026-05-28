<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorStaff;
use Illuminate\Http\Request;

class VendorStaffApiController extends Controller
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

        $staff = VendorStaff::where('vendor_id', $vendorId)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => ['staff' => $staff],
        ]);
    }

    public function store(Request $request)
    {
        $vendorId = $this->vendorId($request);
        if (! $vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'shift_start' => 'required|string',
            'shift_end' => 'required|string',
        ]);

        $staff = VendorStaff::create([
            'vendor_id' => $vendorId,
            'name' => $request->name,
            'role' => $request->role,
            'phone' => $request->phone,
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'performance_score' => 80,
            'is_online' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff member added successfully',
            'data' => ['staff' => $staff],
        ], 201);
    }
}

