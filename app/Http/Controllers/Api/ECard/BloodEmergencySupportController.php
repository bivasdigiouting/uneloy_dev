<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardSevaEmergencyOtherPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodEmergencySupportController extends Controller
{
    private function isBloodEmergency(ECardSevaEmergencyOtherPoint $row): bool
    {
        // emergency_type uses our API controller values (blood / blood_urgent / blood_message / blood_*).
        $t = (string) ($row->emergency_type ?? '');
        return stripos($t, 'blood') !== false;
    }

    /**
     * 1) show all requests, and status of requests
     */
    public function listRequests(Request $request)
    {
        $limit = (int) $request->integer('limit', 50);
        $limit = max(1, min($limit, 200));

        $status = $request->string('status')->toString();
        $query = ECardSevaEmergencyOtherPoint::query()
            ->orderByDesc('request_date');

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        // Include BOTH:
        // 1) urgent blood help requests (from emergency table)
        // 2) donor requests + donor messages (from blood_donor_requests/blood_donor_messages)

        $urgentRows = $query
            ->limit($limit)
            ->get()
            ->filter(function ($r) {
                $t = strtolower((string) ($r->emergency_type ?? ''));
                return str_contains($t, 'blood_urgent') || $t === 'blood_urgent' || str_contains($t, 'blood urgent');
            })
            ->values();

        $urgentItems = $urgentRows->map(function (ECardSevaEmergencyOtherPoint $r) {
            return [
                'type' => 'urgent',
                'id' => (int) $r->id,
                'name' => (string) ($r->name ?? '-'),
                'mobileNo' => $r->mobile_no !== null ? (string) $r->mobile_no : null,
                'emergencyType' => $r->emergency_type !== null ? (string) $r->emergency_type : null,
                'status' => $r->status !== null ? (string) $r->status : null,
                'description' => $r->description !== null ? (string) $r->description : null,
                'liveLocation' => $r->live_location !== null ? (string) $r->live_location : null,
                'requestDate' => $r->request_date?->toDateTimeString(),
            ];
        });

        $donorRequestsQ = \App\Models\BloodDonorRequest::query()
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $donorRequestsQ->where('status', $status);
        }

        $donorRequests = $donorRequestsQ->limit($limit)->get();

        $donorItems = $donorRequests->map(function (\App\Models\BloodDonorRequest $r) {
            $lastMessage = $r->messages()->orderByDesc('created_at')->first();
            return [
                'type' => 'donor_request',
                'id' => (int) $r->id,
                'donorId' => $r->donor_id !== null ? (int) $r->donor_id : null,
                'donorName' => $r->donor_name !== null ? (string) $r->donor_name : null,
                'donorMobileNo' => $r->donor_mobile_no !== null ? (string) $r->donor_mobile_no : null,
                'bloodGroup' => $r->blood_group !== null ? (string) $r->blood_group : null,
                'requesterName' => $r->requester_name !== null ? (string) $r->requester_name : null,
                'requesterMobileNo' => $r->requester_mobile_no !== null ? (string) $r->requester_mobile_no : null,
                'status' => $r->status !== null ? (string) $r->status : null,
                'notes' => $r->notes !== null ? (string) $r->notes : null,
                'lastMessage' => $lastMessage ? (string) $lastMessage->message : null,
                'requestDate' => $r->created_at?->toDateTimeString(),
            ];
        });

        $items = $urgentItems->merge($donorItems)->values();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $items->count(),
                'items' => $items,
            ],
        ]);
    }
}

