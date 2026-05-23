<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/**
 * @group QR Code
 *
 * APIs for generating and viewing QR codes for ECard users.
 */
class QrCodeController extends Controller
{
    /**
     * Generate QR Code
     *
     * Generate a QR code for the authenticated user based on their User ID.
     * If the QR code already exists in the database, it returns the existing URL.
     * Otherwise, it generates a new one, saves it, and updates the database.
     *
     * @authenticated
     *
     * @response 200 {
     *  "message": "QR Code generated successfully",
     *  "url": "http://localhost/storage/qrcodes/user_1.png"
     * }
     */
    public function generate(Request $request)
    {
        $user = $request->user();
        $disk = Storage::disk('public');
        $directory = 'qrcodes';
        $fileName = 'user_' . $user->id . '.png';
        $relativePath = $directory . '/' . $fileName;

        // Check if QR code is already in DB and file exists
        if ($user->qr_code && $disk->exists($user->qr_code)) {
            return response()->json([
                'message' => 'QR Code already exists',
                'url' => asset('storage/' . $user->qr_code)
            ]);
        }

        // Ensure directory exists
        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        $fullPath = $disk->path($relativePath);

        // Generate QR Code
        // Format: png, Size: 300x300, Content: User ID
        QrCode::format('png')
              ->size(300)
              ->generate((string)$user->id, $fullPath);

        // Save relative path to database
        $user->qr_code = $relativePath;
        $user->save();

        $url = asset('storage/' . $relativePath);

        return response()->json([
            'message' => 'QR Code generated successfully',
            'url' => $url
        ]);
    }

    /**
     * View QR Code
     *
     * Retrieve the generated QR code image for the authenticated user.
     * Uses the path stored in the database if available.
     *
     * @authenticated
     *
     * @response 200 <<binary data>>
     * @response 404 {
     *  "message": "QR Code not found. Please generate it first."
     * }
     */
    public function view(Request $request)
    {
        $user = $request->user();
        $relativePath = $user->qr_code;
        $disk = Storage::disk('public');

        if (!$relativePath || !$disk->exists($relativePath)) {
            // Fallback: Check if file exists in default location even if not in DB (backward compatibility)
            $fallbackPath = 'qrcodes/user_' . $user->id . '.png';
            if ($disk->exists($fallbackPath)) {
                $relativePath = $fallbackPath;
                // Update DB for future consistency
                $user->qr_code = $relativePath;
                $user->save();
            } else {
                return response()->json([
                    'message' => 'QR Code not found. Please generate it first.'
                ], 404);
            }
        }

        $fullPath = $disk->path($relativePath);

        return response()->file($fullPath);
    }
}
