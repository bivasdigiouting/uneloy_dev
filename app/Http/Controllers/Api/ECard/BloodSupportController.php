<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\BloodDonateOtherPoint;
use App\Models\ECardSevaEmergencyOtherPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodDonorRequest;
use App\Models\BloodDonorMessage;

use Illuminate\Validation\Rule;



class BloodSupportController extends Controller
{
    private function userId(): int
    {
        $user = Auth::guard('sanctum')->user();
        return (int) ($user?->id ?? 0);
    }

    /**
     * 1) search for donors
     */
    public function searchDonors(Request $request)
    {
        $query = BloodDonateOtherPoint::query();

        // Optional filters (only apply when present)
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->string('blood_group')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', $request->string('mobile_no')->toString());
        }

        if ($request->filled('hospital_name')) {
            $query->where('hospital_name', 'like', '%' . $request->string('hospital_name')->toString() . '%');
        }

        // Basic search across name/composite
        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', '%' . $q . '%')
                    ->orWhere('approved_name', 'like', '%' . $q . '%')
                    ->orWhere('mobile_no', 'like', '%' . $q . '%');
            });
        }

        $limit = (int) $request->integer('limit', 20);
        $limit = max(1, min($limit, 100));

        // In absence of explicit schema fields (state/district/city), we only sort by latest request/proof.
        // Prefer approved_date if present.
        $rows = $query
            ->orderByDesc('send_points_date')
            ->orderByDesc('request_date')
            ->take($limit)
            ->get();

        $items = $rows->map(function (BloodDonateOtherPoint $d) {
            return [
                'id' => (int) $d->id,
                'name' => (string) ($d->name ?? '-'),
                'mobileNo' => $d->mobile_no !== null ? (string) $d->mobile_no : null,
                'bloodGroup' => $d->blood_group !== null ? (string) $d->blood_group : null,
                'hospitalName' => $d->hospital_name !== null ? (string) $d->hospital_name : null,
                'status' => $d->status !== null ? (string) $d->status : null,
                'approvedComposite' => $d->approved_composite,
                'requestDate' => $d->request_date?->toDateTimeString(),
                'sendPointsDate' => $d->send_points_date?->toDateTimeString(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $items->count(),
                'items' => $items,
            ],
        ]);
    }

    /**
     * 2) view donors details
     */
    public function getDonorDetails(Request $request, int $id)
    {
        $donor = BloodDonateOtherPoint::query()->find($id);

        if (! $donor) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Donor not found', 'code' => 'DONOR_NOT_FOUND'],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (int) $donor->id,
                'name' => (string) ($donor->name ?? '-'),
                'mobileNo' => $donor->mobile_no !== null ? (string) $donor->mobile_no : null,
                'age' => $donor->age !== null ? (int) $donor->age : null,
                'gender' => $donor->gender !== null ? (string) $donor->gender : null,
                'bloodGroup' => $donor->blood_group !== null ? (string) $donor->blood_group : null,
                'hospitalName' => $donor->hospital_name !== null ? (string) $donor->hospital_name : null,
                'hospitalAddress' => $donor->hospital_address !== null ? (string) $donor->hospital_address : null,
                'status' => $donor->status !== null ? (string) $donor->status : null,
                'approvedComposite' => $donor->approved_composite,
                'requestDate' => $donor->request_date?->toDateTimeString(),
                'sendPoints' => $donor->send_points !== null ? (int) $donor->send_points : null,
                'sendPointsRemarks' => $donor->send_points_remarks !== null ? (string) $donor->send_points_remarks : null,
                'sendPointsDate' => $donor->send_points_date?->toDateTimeString(),
            ],
        ]);
    }

    /**
     * 2) request donor
     */
    public function requestDonor(Request $request, int $id)
    {
        $donor = BloodDonateOtherPoint::query()->find($id);

        if (! $donor) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Donor not found', 'code' => 'DONOR_NOT_FOUND'],
            ], 404);
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'emergencyType' => ['nullable', 'string', 'max:100'],
            'bloodGroup' => ['nullable', 'string', 'max:20'],
        ]);

        // No dedicated donor-request table exists in current schema.
        // Reuse emergency table as "blood" requests so the user can later view status.
        $user = Auth::guard('sanctum')->user();

        $req = BloodDonorRequest::create([
            'requester_user_id' => $user?->id,
            'requester_name' => $user?->name,
            'requester_mobile_no' => $user?->mobile_no,
            'donor_id' => $donor->id,
            'donor_name' => $donor->name,
            'donor_mobile_no' => $donor->mobile_no,
            'blood_group' => $validated['bloodGroup'] ?: $donor->blood_group,
            'status' => 'pending',
            'notes' => $validated['message'] ?? 'Blood donor requested',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'requestId' => (int) $req->id,
                'status' => (string) $req->status,
            ],
        ]);
    }

    /**
     * 2) send msg (persisted into description of the reused emergency request)
     */
    public function sendDonorMessage(Request $request, int $id)
    {
        $donor = BloodDonateOtherPoint::query()->find($id);

        if (! $donor) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Donor not found', 'code' => 'DONOR_NOT_FOUND'],
            ], 404);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = Auth::guard('sanctum')->user();

        // If donor request doesn't exist, create it, then store message.
        $req = BloodDonorRequest::create([
            'requester_user_id' => $user?->id,
            'requester_name' => $user?->name,
            'requester_mobile_no' => $user?->mobile_no,
            'donor_id' => $donor->id,
            'donor_name' => $donor->name,
            'donor_mobile_no' => $donor->mobile_no,
            'blood_group' => $donor->blood_group,
            'status' => 'pending',
            'notes' => 'Message sent to donor',
        ]);

        BloodDonorMessage::create([
            'blood_donor_request_id' => $req->id,
            'sender_user_id' => $user?->id,
            'sender_name' => $user?->name,
            'sender_mobile_no' => $user?->mobile_no,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'requestId' => (int) $req->id,
                'status' => (string) $req->status,
            ],
        ]);
    }

    /**
     * 3) request urgent blood help
     */
    public function requestUrgentBlood(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'mobile_no' => ['required', 'string', 'max:30'],
            'blood_group' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:2000'],
            'live_location' => ['nullable', 'string', 'max:500'],
        ]);

        // Urgent help remains in existing emergency table.
        $row = ECardSevaEmergencyOtherPoint::create([
            'points' => 0,
            'approved_id_no' => null,
            'approved_name' => null,
            'approved_date' => now(),
            'name' => $validated['name'],
            'mobile_no' => $validated['mobile_no'],
            'emergency_type' => 'blood_urgent',
            'age' => null,
            'gender' => null,
            'live_location' => $validated['live_location'] ?? null,
            'description' => $validated['description'] ?? ($validated['blood_group'] ? ('Urgent blood help for ' . $validated['blood_group']) : 'Urgent blood help'),
            'request_date' => now(),
            'image' => null,
            'status' => 'pending',
            'send_points_remarks' => null,
            'send_points_date' => null,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'requestId' => (int) $row->id,
                'status' => (string) $row->status,
            ],
        ]);
    }
}

