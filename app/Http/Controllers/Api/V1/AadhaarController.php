<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserAadhaar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class AadhaarController extends Controller
{
    /**
     * Get Aadhaar front/back image URLs for the authenticated user.
     *
     * @group KYC
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Aadhaar image URLs fetched successfully.",
     *   "data": {
     *     "front_image_url": "https://example.com/storage/aadhaar/1/front.jpg",
     *     "back_image_url": "https://example.com/storage/aadhaar/1/back.jpg"
     *   }
     * }
     *
     * @OA\Get(
     *   path="/api/v1/auth/identity/aadhaar",
     *   summary="Get Aadhaar image URLs",
     *   tags={"Authentication"},
     *   security={{"sanctum":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Aadhaar image URLs",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="front_image_url", type="string", nullable=true),
     *         @OA\Property(property="back_image_url", type="string", nullable=true)
     *       )
     *     )
     *   )
     * )
     */
    public function getAadhaar(Request $request)
    {
        $user = $request->user();
        $record = UserAadhaar::where('user_id', $user->id)->first();

        $frontUrl = null;
        $backUrl = null;

        if ($record && $record->front_image && Storage::disk('public')->exists($record->front_image)) {
            $frontUrl = url(Storage::url($record->front_image));
        }
        if ($record && $record->back_image && Storage::disk('public')->exists($record->back_image)) {
            $backUrl = url(Storage::url($record->back_image));
        }

        return response()->json([
            'success' => true,
            'message' => 'Aadhaar image URLs fetched successfully.',
            'data' => [
                'front_image_url' => $frontUrl,
                'back_image_url' => $backUrl,
            ],
        ]);
    }

    /**
     * Upload or update Aadhaar front/back images for the authenticated user.
     *
     * - Accepts two optional files: `aadhaar_front` and `aadhaar_back`.
     * - Stores files on the public disk and saves paths in the database.
     * - Returns full URLs (with base URL) for both images.
     *
     * @group KYC
     *
     * @authenticated
     *
     * @bodyParam aadhaar_front file optional The Aadhaar front image file.
     * @bodyParam aadhaar_back file optional The Aadhaar back image file.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Aadhaar images updated successfully.",
     *   "data": {
     *     "front_image_url": "https://example.com/storage/aadhaar/1/front.jpg",
     *     "back_image_url": "https://example.com/storage/aadhaar/1/back.jpg"
     *   }
     * }
     *
     * @OA\Post(
     *   path="/api/v1/auth/identity/aadhaar",
     *   summary="Upload/update Aadhaar images",
     *   tags={"Authentication"},
     *   security={{"sanctum":{}}},
     *
     *   @OA\RequestBody(
     *     required=false,
     *
     *     @OA\MediaType(mediaType="multipart/form-data",
     *
     *       @OA\Schema(
     *         type="object",
     *
     *         @OA\Property(property="aadhaar_front", type="string", format="binary"),
     *         @OA\Property(property="aadhaar_back", type="string", format="binary")
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Aadhaar images updated",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="front_image_url", type="string", nullable=true),
     *         @OA\Property(property="back_image_url", type="string", nullable=true)
     *       )
     *     )
     *   )
     * )
     */
    public function updateAadhaar(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'aadhaar_front' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'aadhaar_back' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $dir = "aadhaar/{$user->id}";
        $frontPath = null;
        $backPath = null;

        if ($request->hasFile('aadhaar_front')) {
            $frontPath = $request->file('aadhaar_front')->store($dir, 'public');
        }
        if ($request->hasFile('aadhaar_back')) {
            $backPath = $request->file('aadhaar_back')->store($dir, 'public');
        }

        $record = UserAadhaar::firstOrCreate(['user_id' => $user->id]);
        $updateData = [];
        if ($frontPath) {
            $updateData['front_image'] = $frontPath;
        }
        if ($backPath) {
            $updateData['back_image'] = $backPath;
        }
        if (! empty($updateData)) {
            $record->update($updateData);
        }

        $frontUrl = ($record->front_image && Storage::disk('public')->exists($record->front_image))
            ? url(Storage::url($record->front_image))
            : null;
        $backUrl = ($record->back_image && Storage::disk('public')->exists($record->back_image))
            ? url(Storage::url($record->back_image))
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Aadhaar images updated successfully.',
            'data' => [
                'front_image_url' => $frontUrl,
                'back_image_url' => $backUrl,
            ],
        ]);
    }
}
