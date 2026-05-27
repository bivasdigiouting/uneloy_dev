<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SevaUsersController extends Controller
{
    private function parseStatus(string $status): ?string
    {
        $s = strtolower(trim($status));
        if ($s === '') {
            return null;
        }

        // allowed: verified/unverified
        return match ($s) {
            'verified' => 'verified',
            'unverified' => 'unverified',
            default => null,
        };
    }

    private function isVerifiedRow(ECardRegistration $r): bool
    {
        // Repo uses KYC upload docs for admin approval.
        // We treat:
        // - rejected => unverified
        // - else: complete docs => verified
        $hasAll = ! empty($r->pan_no)
            && ! empty($r->aadhaar_no)
            && ! empty($r->bank_name)
            && ! empty($r->account_no)
            && ! empty($r->ifsc_code);

        return strtolower((string) $r->status) !== 'rejected' && $hasAll;
    }

    private function toUserDetails(ECardRegistration $r): array
    {
        return [
            'id' => (int) $r->id,
            'member_id' => $r->user_id !== null ? (string) $r->user_id : null,
            'full_name' => trim((string) ($r->first_name ?? '').' '.($r->middle_name ?? '').' '.($r->last_name ?? '')),
            'email' => $r->email_id !== null ? (string) $r->email_id : null,
            'mobile_no' => $r->mobile_no !== null ? (string) $r->mobile_no : null,
            'kyc' => [
                'status' => $r->status !== null ? (string) $r->status : null,
                'kyc_state' => $this->isVerifiedRow($r) ? 'verified' : 'unverified',
            ],
            'kyc_documents' => [
                'pan_no' => $r->pan_no !== null ? (string) $r->pan_no : null,
                'aadhaar_no' => $r->aadhaar_no !== null ? (string) $r->aadhaar_no : null,
                'bank_name' => $r->bank_name !== null ? (string) $r->bank_name : null,
                'account_no' => $r->account_no !== null ? (string) $r->account_no : null,
                'ifsc_code' => $r->ifsc_code !== null ? (string) $r->ifsc_code : null,
            ],
            'created_at' => $r->created_at?->toDateTimeString(),
            'updated_at' => $r->updated_at?->toDateTimeString(),
        ];
    }

    /**
     * 1) list of registered users with status verified/unverified
     * 2) verified users details
     * 3) unverified users: complete verification process (admin-style approval)
     *
     * Note: In this codebase, KYC verification is for ecard_registrations.
     * You can reuse this for ecard seva/eseva/health if they share the same user module.
     */

    public function listUsers(Request $request)
    {
        $request->validate([
            'status' => 'nullable|string|in:verified,unverified',
            'limit' => 'nullable|integer|min:1|max:200',
        ]);

        $limit = (int) $request->integer('limit', 50);
        $status = $request->string('status', '')->toString();

        $qb = ECardRegistration::query()->orderByDesc('created_at');

        $rows = $qb->limit($limit)->get();

        if ($status !== '') {
            $filter = $this->parseStatus($status);
            $rows = $rows->filter(function (ECardRegistration $r) use ($filter) {
                $rowState = $this->isVerifiedRow($r) ? 'verified' : 'unverified';
                return $rowState === $filter;
            });
        }

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $rows->count(),
                'items' => $rows->map(fn ($r) => $this->toUserDetails($r))->values(),
            ],
        ]);
    }

    public function verifiedDetails(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|min:1',
        ]);

        $id = (int) $request->input('user_id');
        $r = ECardRegistration::query()->find($id);

        if (! $r) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (! $this->isVerifiedRow($r)) {
            return response()->json(['message' => 'User is not verified'], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $this->toUserDetails($r),
        ]);
    }

    public function completeVerification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|min:1',
            'status' => 'required|in:approved,rejected',
        ]);

        $id = (int) $request->input('user_id');
        $status = (string) $request->input('status');

        $r = ECardRegistration::query()->find($id);
        if (! $r) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Mirror admin: update status.
        // For rejected -> set status=rejected.
        // For approved -> status=approved, and verified is derived from required doc fields.
        $r->status = $status === 'rejected' ? 'rejected' : 'approved';
        $r->save();

        return response()->json([
            'success' => true,
            'message' => $status === 'rejected' ? 'KYC rejected' : 'KYC approved',
            'data' => $this->toUserDetails($r),
        ]);
    }
}

