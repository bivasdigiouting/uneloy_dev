<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeviceSharingController extends Controller
{
    /**
     * Get Device Sharing Info
     *
     * @group Device Sharing
     *
     * @authenticated
     *
     * Returns the current device sharing setup for the logged-in customer.
     * Includes the device number, sharing status, and the max allowed shared devices (default 1).
     *
     * @OA\Get(
     *     path="/api/v1/auth/device-sharing",
     *     tags={"Device Sharing"},
     *     security={{"sanctum":{}}},
     *     summary="Get Device Sharing Info",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Device sharing info",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="device_number", type="string", example="ABC123-IMEI"),
     *                 @OA\Property(property="enabled", type="boolean", example=true),
     *                 @OA\Property(property="max_devices", type="integer", example=1)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function show(Request $request)
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
            return response()->json(['success' => false, 'message' => 'Only customers can access device sharing'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'device_number' => $registration->device_number,
                'enabled' => (bool) $registration->device_sharing_enabled,
                'max_devices' => (int) ($registration->max_device_shares ?? 1),
            ],
        ]);
    }

    /**
     * Update Device Number
     *
     * @group Device Sharing
     *
     * @authenticated
     *
     * Updates the device number associated with the logged-in customer's registration.
     * This module supports only one device per registration by default.
     *
     * @OA\Put(
     *     path="/api/v1/auth/device-sharing",
     *     tags={"Device Sharing"},
     *     security={{"sanctum":{}}},
     *     summary="Update Device Number",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"device_number"},
     *
     *             @OA\Property(property="device_number", type="string", example="ABC123-IMEI")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Device number updated"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function update(Request $request)
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
            return response()->json(['success' => false, 'message' => 'Only customers can update device number'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'device_number' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $registration->device_number = $validator->validated()['device_number'];
        // Persist default max to ensure only one device by default
        if (empty($registration->max_device_shares)) {
            $registration->max_device_shares = 1;
        }
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Device number updated',
            'data' => [
                'device_number' => $registration->device_number,
                'enabled' => (bool) $registration->device_sharing_enabled,
                'max_devices' => (int) ($registration->max_device_shares ?? 1),
            ],
        ]);
    }

    /**
     * Get Device Sharing Status
     *
     * @group Device Sharing
     *
     * @authenticated
     *
     * Fetches whether device sharing is enabled for the logged-in customer's registration.
     *
     * @OA\Get(
     *     path="/api/v1/auth/device-sharing/status",
     *     tags={"Device Sharing"},
     *     security={{"sanctum":{}}},
     *     summary="Get Device Sharing Status",
     *
     *     @OA\Response(response=200, description="Device sharing status")
     * )
     */
    public function status(Request $request)
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
            return response()->json(['success' => false, 'message' => 'Only customers can access device sharing status'], 403);
        }

        return response()->json([
            'success' => true,
            'enabled' => (bool) $registration->device_sharing_enabled,
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
