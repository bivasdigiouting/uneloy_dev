<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class ShareProfileEcardSevaController extends Controller
{
    /**
     * Get share profile to E-Card Seva status.
     *
     * @group KYC
     *
     * @authenticated
     *
     * @response 200 {"success":true,"message":"Share profile status fetched.","data":{"share_profile":true}}
     *
     * @OA\Get(
     *   path="/api/v1/auth/ecard-seva/share-profile",
     *   summary="Get share profile status for E-Card Seva",
     *   tags={"Authentication"},
     *   security={{"sanctum":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Share profile status",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="share_profile", type="boolean")
     *       )
     *     )
     *   )
     * )
     */
    public function getStatus(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $registration = $this->findRegistrationForUser($user);
        if (! $registration) {
            return response()->json(['success' => false, 'message' => 'Registration not found for user'], 404);
        }
        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Only customers can access this endpoint'], 403);
        }

        $value = (bool) ($registration->share_profile_to_ecard_seva ?? false);

        return response()->json([
            'success' => true,
            'message' => 'Share profile status fetched.',
            'data' => [
                'share_profile' => $value,
            ],
        ]);
    }

    /**
     * Update share profile to E-Card Seva status.
     *
     * - Accepts `share_profile` as a boolean (true/false).
     * - Persists to `registrations.share_profile_to_ecard_seva`.
     *
     * @group KYC
     *
     * @authenticated
     *
     * @bodyParam share_profile boolean required Whether to share profile to E-Card Seva.
     *
     * @response 200 {"success":true,"message":"Share profile status updated.","data":{"share_profile":true}}
     *
     * @OA\Post(
     *   path="/api/v1/auth/ecard-seva/share-profile",
     *   summary="Update share profile status for E-Card Seva",
     *   tags={"Authentication"},
     *   security={{"sanctum":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\MediaType(mediaType="application/json",
     *
     *       @OA\Schema(
     *         type="object",
     *         required={"share_profile"},
     *
     *         @OA\Property(property="share_profile", type="boolean")
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Updated share profile status",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="share_profile", type="boolean")
     *       )
     *     )
     *   )
     * )
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'share_profile' => 'required|boolean',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $registration = $this->findRegistrationForUser($user);
        if (! $registration) {
            return response()->json(['success' => false, 'message' => 'Registration not found for user'], 404);
        }
        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Only customers can update this setting'], 403);
        }

        $registration->share_profile_to_ecard_seva = (bool) $validated['share_profile'];
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Share profile status updated.',
            'data' => [
                'share_profile' => (bool) $registration->share_profile_to_ecard_seva,
            ],
        ]);
    }

    private function findRegistrationForUser($user): ?Registration
    {
        $registration = null;
        if (! empty($user->user_id)) {
            $registration = Registration::where('user_id', $user->user_id)->first();
        }
        if (! $registration && ! empty($user->email)) {
            $registration = Registration::where('email_id', $user->email)->first();
        }
        if (! $registration) {
            $registration = Registration::where('user_id', $user->id)->first();
        }

        return $registration;
    }
}
