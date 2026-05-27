<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardLoginHistory;
use App\Models\ECardUserKyc;
use App\Models\ECardWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileDetailController extends Controller
{
    /**
     * Documents: return KYC documents & verification status
     */
    public function documents(Request $request)
    {
        $user = $request->user();

        $kyc = ECardUserKyc::query()
            ->where('ecard_registration_id', $user->id)
            ->first();

        $docs = [
            'aadhaar_front' => $kyc?->aadhaar_front,
            'aadhaar_back' => $kyc?->aadhaar_back,
            'pan_front' => $kyc?->pan_front,
            'pan_back' => $kyc?->pan_back,
            'cheque_book' => $kyc?->cheque_book,
            'business_document' => $kyc?->business_document,
            'business_photo' => $kyc?->business_photo,
            'signature' => $kyc?->signature,
            // URLs
            'aadhaar_front_url' => $kyc?->aadhaar_front ? asset('storage/' . $kyc->aadhaar_front) : null,
            'aadhaar_back_url' => $kyc?->aadhaar_back ? asset('storage/' . $kyc->aadhaar_back) : null,
            'pan_front_url' => $kyc?->pan_front ? asset('storage/' . $kyc->pan_front) : null,
            'pan_back_url' => $kyc?->pan_back ? asset('storage/' . $kyc->pan_back) : null,
            'cheque_book_url' => $kyc?->cheque_book ? asset('storage/' . $kyc->cheque_book) : null,
            'business_document_url' => $kyc?->business_document ? asset('storage/' . $kyc->business_document) : null,
            'business_photo_url' => $kyc?->business_photo ? asset('storage/' . $kyc->business_photo) : null,
            'signature_url' => $kyc?->signature ? asset('storage/' . $kyc->signature) : null,
        ];

        // Verification status based on ECardRegistration fields
        $verified = ! empty($user->pan_no)
            && ! empty($user->aadhaar_no)
            && ! empty($user->bank_name)
            && ! empty($user->account_no)
            && ! empty($user->ifsc_code)
            && strtolower((string) $user->status) !== 'rejected';

        return response()->json([
            'success' => true,
            'data' => [
                'kyc' => $docs,
                'verification' => [
                    'status' => $user->status !== null ? (string) $user->status : null,
                    'state' => $verified ? 'verified' : 'unverified',
                ],
            ],
        ]);
    }

    /**
     * Activity: wallet transactions + login history (basic UI feed)
     */
    public function activity(Request $request)
    {
        $user = $request->user();

        $walletTx = ECardWalletTransaction::query()
            ->where('ecard_registration_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $loginHistory = ECardLoginHistory::query()
            ->where('ecard_registration_id', $user->id)
            ->orderByDesc('logged_in_at')
            ->limit(20)
            ->get();

        $walletItems = $walletTx->map(function (ECardWalletTransaction $t) {
            return [
                'type' => 'wallet',
                'id' => (int) $t->id,
                'created_at' => $t->created_at?->toDateTimeString(),
                'direction' => match ((string) $t->transaction_type) {
                    'transfer_out' => 'out',
                    'transfer_in' => 'in',
                    default => 'unknown',
                },
                'amount' => (float) $t->amount,
                'narration' => (string) ($t->narration ?? ''),
                'reference_type' => (string) ($t->reference_type ?? ''),
            ];
        });

        $loginItems = $loginHistory->map(function (ECardLoginHistory $h) {
            return [
                'type' => 'login',
                'id' => (int) $h->id,
                'created_at' => $h->logged_in_at?->toDateTimeString(),
                'ip_address' => $h->ip_address,
                'platform' => $h->platform,
                'user_agent' => $h->user_agent,
            ];
        });

        // Merge & sort by created_at desc
        $merged = $walletItems
            ->concat($loginItems)
            ->sortByDesc(fn ($i) => isset($i['created_at']) ? strtotime($i['created_at']) : 0)
            ->values()
            ->take(30);

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $merged,
            ],
        ]);
    }

    /**
     * Device sharing settings + logged in devices
     * NOTE: there is no explicit 'devices' table for ecard, so we return login history sessions.
     */
    public function devicePermission(Request $request)
    {
        $user = $request->user();

        $enabled = (bool) ($user->device_sharing_enabled ?? true);
        $max = (int) ($user->max_device_shares ?? 1);

        // Current logged in devices are approximated by login history not logged out
        $sessions = ECardLoginHistory::query()
            ->where('ecard_registration_id', $user->id)
            ->whereNull('logged_out_at')
            ->orderByDesc('logged_in_at')
            ->limit(50)
            ->get()
            ->map(function (ECardLoginHistory $h) {
                return [
                    'id' => (int) $h->id,
                    'device_number' => null,
                    'ip_address' => $h->ip_address,
                    'platform' => $h->platform,
                    'user_agent' => $h->user_agent,
                    'logged_in_at' => $h->logged_in_at?->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'device_sharing_enabled' => $enabled,
                'max_device_shares' => $max,
                'logged_in_devices' => $sessions,
                'logged_in_count' => $sessions->count(),
            ],
        ]);
    }

    public function setDevicePermission(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'device_sharing_enabled' => 'required|boolean',
            'max_device_shares' => 'required|integer|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $user->device_sharing_enabled = (bool) $data['device_sharing_enabled'];
        $user->max_device_shares = (int) $data['max_device_shares'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Device permissions updated successfully.',
            'data' => [
                'device_sharing_enabled' => (bool) $user->device_sharing_enabled,
                'max_device_shares' => (int) $user->max_device_shares,
            ],
        ]);
    }

    /**
     * Password / MPIN / biometric / nfc / email+phone verification endpoints are
     * not fully defined in the current repo schema for ecard.
     * We expose only what exists: change MPIN + change password is outside current ecard APIs.
     */
}

