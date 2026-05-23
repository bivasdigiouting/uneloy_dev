<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    /**
     * Generate QR
     *
     * @group Registration
     *
     * @authenticated
     *
     * Generate a dynamic QR code using the logged-in user's `user_id` from the registrations table.
     * Only available when the associated registration has `department_level = customer`.
     *
     * @OA\Post(
     *     path="/api/v1/auth/my-qr/generate",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *     summary="Generate QR",
     *     description="Creates a PNG QR image based on the registration's user_id and stores it on the public disk.",
     *
     *     @OA\Response(
     *         response=201,
     *         description="QR code generated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="QR code generated"),
     *             @OA\Property(property="file", type="string", example="qr-codes/user-UP12345678.png"),
     *             @OA\Property(property="url", type="string", example="http://127.0.0.1:8000/api/v1/auth/my-qr")
     *         )
     *     ),
     *
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level"),
     *     @OA\Response(response=404, description="Registration not found for user")
     * )
     */
    public function generateMyQr()
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $registration = null;
        if (! empty($user->user_id)) {
            $registration = Registration::where('user_id', $user->user_id)->first();
        }
        if (! $registration && ! empty($user->email)) {
            $registration = Registration::where('email_id', $user->email)->first();
        }
        if (! $registration) {
            return response()->json(['message' => 'Registration not found for user'], 404);
        }

        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['message' => 'Only customers can generate QR'], 403);
        }

        $content = (string) ($registration->user_id ?? $user->user_id ?? $user->id);
        $png = QrCode::format('png')->size(400)->margin(1)->errorCorrection('H')->generate($content);

        $relativePath = 'qr-codes/user-'.$content.'.png';
        Storage::disk('public')->put($relativePath, $png);

        return response()->json([
            'message' => 'QR code generated',
            'file' => $relativePath,
            'url' => url('/api/v1/auth/my-qr'),
        ], 201);
    }

    /**
     * Fetch QR
     *
     * @group Registration
     *
     * @authenticated
     *
     * Returns the PNG QR image generated for the logged-in user's registration.
     * Requires the registration to have `department_level = customer` and the QR to be generated first.
     *
     * @OA\Get(
     *     path="/api/v1/auth/my-qr",
     *     tags={"Registration"},
     *     security={{"sanctum":{}}},
     *     summary="Fetch QR",
     *
     *     @OA\Response(response=200, description="PNG image returned"),
     *     @OA\Response(response=404, description="QR not generated or registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function getMyQr()
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $registration = null;
        if (! empty($user->user_id)) {
            $registration = Registration::where('user_id', $user->user_id)->first();
        }
        if (! $registration && ! empty($user->email)) {
            $registration = Registration::where('email_id', $user->email)->first();
        }
        if (! $registration) {
            return response()->json(['message' => 'Registration not found for user'], 404);
        }

        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['message' => 'Only customers can fetch QR'], 403);
        }

        $content = (string) ($registration->user_id ?? $user->user_id ?? $user->id);
        $relativePath = 'qr-codes/user-'.$content.'.png';

        if (! Storage::disk('public')->exists($relativePath)) {
            return response()->json(['message' => 'QR not generated yet'], 404);
        }

        $png = Storage::disk('public')->get($relativePath);

        return response($png, 200)->header('Content-Type', 'image/png');
    }
}
