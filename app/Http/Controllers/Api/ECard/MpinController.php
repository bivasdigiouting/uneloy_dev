<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MpinController extends Controller
{
    /**
     * Update MPIN
     * 
     * Update or set the MPIN for the authenticated user.
     * 
     * @group MPIN Management
     * @authenticated
     * 
     * @bodyParam mpin string required The new MPIN (4-6 digits). Example: 1234
     * 
     * @response 200 {
     *  "message": "MPIN updated successfully"
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "mpin": ["The mpin must be between 4 and 6 digits."]
     *  }
     * }
     */
    public function update(Request $request)
    {
        $request->validate([
            'mpin' => 'required|digits_between:4,6',
        ]);

        $user = $request->user();
        $user->mpin = Hash::make($request->mpin);
        $user->save();

        return response()->json([
            'message' => 'MPIN updated successfully',
        ]);
    }

    /**
     * Verify MPIN
     * 
     * Verify the MPIN for the authenticated user.
     * 
     * @group MPIN Management
     * @authenticated
     * 
     * @bodyParam mpin string required The MPIN to verify. Example: 1234
     * 
     * @response 200 {
     *  "message": "MPIN verified successfully"
     * }
     * @response 422 {
     *  "message": "The given data was invalid.",
     *  "errors": {
     *    "mpin": ["Invalid MPIN."]
     *  }
     * }
     */
    public function verify(Request $request)
    {
        $request->validate([
            'mpin' => 'required',
        ]);

        $user = $request->user();

        if (! $user->mpin || ! Hash::check($request->mpin, $user->mpin)) {
             throw ValidationException::withMessages([
                'mpin' => ['Invalid MPIN.'],
            ]);
        }

        return response()->json([
            'message' => 'MPIN verified successfully',
        ]);
    }
}
