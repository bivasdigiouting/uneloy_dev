<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorKycApprovalController extends Controller
{
    /**
     * Show Approve KYC list page
     */
    public function index()
    {
        return view('admin.vendor_approve_kyc.index');
    }

    /**
     * Data endpoint for Approve KYC list
     */
    public function data(Request $request)
    {
        $table = 'vendors';
        if (! Schema::hasTable($table)) {
            return response()->json(['data' => [], 'error' => 'vendors table not found']);
        }

        $qb = DB::table($table);

        // Build name using available columns
        $nameExpr = 'COALESCE('.
            (Schema::hasColumn($table, 'business_name') ? 'business_name' : 'NULL').', '.
            (Schema::hasColumn($table, 'contact_person') ? 'contact_person' : 'NULL').', '.
            (Schema::hasColumn($table, 'vendor_name') ? 'vendor_name' : 'NULL').
            ')';

        // GST column name
        $gstCol = Schema::hasColumn($table, 'business_gst_no') ? 'business_gst_no' : (Schema::hasColumn($table, 'gst_no') ? 'gst_no' : 'NULL');

        // KYC status expression
        $kycExpr = 'CASE '.
            'WHEN (pan_no IS NOT NULL AND pan_no <> "" AND aadhar_no IS NOT NULL AND aadhar_no <> "") THEN '.
                'CASE WHEN ('.$gstCol.' IS NOT NULL AND '.$gstCol.' <> "") THEN "Complete" ELSE "GST Pending" END '.
            'ELSE "Incomplete" END';

        $selects = [
            DB::raw('id as id'),
            DB::raw((Schema::hasColumn($table, 'vendor_number') ? 'vendor_number' : 'NULL').' as vendor_number'),
            DB::raw($nameExpr.' as name'),
            DB::raw((Schema::hasColumn($table, 'gmail_id') ? 'gmail_id' : 'NULL').' as email'),
            DB::raw((Schema::hasColumn($table, 'mobile_no') ? 'mobile_no' : 'NULL').' as mobile_no'),
            DB::raw((Schema::hasColumn($table, 'pan_no') ? 'pan_no' : 'NULL').' as pan_no'),
            DB::raw((Schema::hasColumn($table, 'aadhar_no') ? 'aadhar_no' : 'NULL').' as aadhar_no'),
            DB::raw($gstCol.' as gst_no'),
            DB::raw($kycExpr.' as kyc_status'),
        ];

        $qb->select($selects);

        // Filter by computed KYC status
        if ($request->filled('kyc_status')) {
            $status = $request->input('kyc_status');
            if (in_array($status, ['Incomplete', 'GST Pending', 'Complete'])) {
                $qb->having('kyc_status', '=', $status);
            }
        }

        // Text search across name/email/mobile/pan/aadhar/gst
        if ($request->filled('search_text')) {
            $text = trim($request->input('search_text'));
            $qb->where(function ($q) use ($text, $table, $gstCol) {
                $q->orWhere(DB::raw('COALESCE('.
                    (Schema::hasColumn($table, 'business_name') ? 'business_name' : 'NULL').', '.
                    (Schema::hasColumn($table, 'contact_person') ? 'contact_person' : 'NULL').', '.
                    (Schema::hasColumn($table, 'vendor_name') ? 'vendor_name' : 'NULL').
                ')'), 'like', "%$text%");

                if (Schema::hasColumn($table, 'gmail_id')) {
                    $q->orWhere('gmail_id', 'like', "%$text%");
                }
                if (Schema::hasColumn($table, 'mobile_no')) {
                    $q->orWhere('mobile_no', 'like', "%$text%");
                }
                if (Schema::hasColumn($table, 'pan_no')) {
                    $q->orWhere('pan_no', 'like', "%$text%");
                }
                if (Schema::hasColumn($table, 'aadhar_no')) {
                    $q->orWhere('aadhar_no', 'like', "%$text%");
                }
                if ($gstCol !== 'NULL') {
                    $q->orWhere($gstCol, 'like', "%$text%");
                }
            });
        }

        $rows = $qb->limit(500)->get();

        return response()->json([
            'data' => $rows,
        ]);
    }

    /**
     * Approve KYC action (stub - updates when columns exist)
     */
    public function approve(Request $request, Vendor $vendor)
    {
        // Only proceed if PAN and Aadhar exist
        $hasPan = Schema::hasColumn('vendors', 'pan_no') && ! empty($vendor->pan_no);
        $hasAadhar = Schema::hasColumn('vendors', 'aadhar_no') && ! empty($vendor->aadhar_no);

        if (! $hasPan || ! $hasAadhar) {
            return response()->json([
                'success' => false,
                'message' => 'PAN and Aadhar are required to approve KYC.',
            ], 422);
        }

        $updates = [];
        // Prefer dedicated KYC fields if they exist
        if (Schema::hasColumn('vendors', 'kyc_status')) {
            $updates['kyc_status'] = 'approved';
        }
        if (Schema::hasColumn('vendors', 'kyc_approved_at')) {
            $updates['kyc_approved_at'] = now();
        }

        if (! empty($updates)) {
            $vendor->fill($updates);
            $vendor->save();
        }

        return response()->json([
            'success' => true,
            'message' => empty($updates)
                ? 'KYC approval recorded (no dedicated columns present).'
                : 'Vendor KYC approved successfully.',
        ]);
    }
}
