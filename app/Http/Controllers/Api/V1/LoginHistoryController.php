<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\UserLoginHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginHistoryController extends Controller
{
    /**
     * List Login History
     *
     * @group Login History
     *
     * @authenticated
     *
     * Returns the login history for the logged-in customer's registration.
     * Each record includes `ip_address`, `platform`, `user_agent`, `logged_in_at`, and `logged_out_at`.
     * Supports optional filtering by platform and date range.
     *
     * @OA\Get(
     *     path="/api/v1/auth/login-history",
     *     tags={"Login History"},
     *     security={{"sanctum":{}}},
     *     summary="List Login History",
     *
     *     @OA\Parameter(name="platform", in="query", required=false, @OA\Schema(type="string", enum={"web","mobile"})),
     *     @OA\Parameter(name="from", in="query", required=false, @OA\Schema(type="string", format="date-time")),
     *     @OA\Parameter(name="to", in="query", required=false, @OA\Schema(type="string", format="date-time")),
     *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login history listed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", example={
     *                 "current_page": 1,
     *                 "data": {
     *                     {"id": 123, "registration_id": 10, "platform": "mobile", "ip_address": "203.0.113.5", "user_agent": "Dalvik/2.1.0", "logged_in_at": "2025-11-05T10:15:00Z", "logged_out_at": "2025-11-05T11:00:00Z"}
     *                 },
     *                 "per_page": 15,
     *                 "total": 1
     *             })
     *         )
     *     ),
     *
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function index(Request $request)
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
            return response()->json(['success' => false, 'message' => 'Only customers can access login history'], 403);
        }

        $query = UserLoginHistory::where('registration_id', $registration->id)->orderBy('logged_in_at', 'desc');

        $platform = $request->query('platform');
        if ($platform && in_array(strtolower($platform), ['web', 'mobile'], true)) {
            $query->where('platform', strtolower($platform));
        }

        $from = $request->query('from');
        $to = $request->query('to');
        if ($from) {
            try {
                $query->where('logged_in_at', '>=', Carbon::parse($from));
            } catch (\Throwable $e) {
            }
        }
        if ($to) {
            try {
                $query->where('logged_in_at', '<=', Carbon::parse($to));
            } catch (\Throwable $e) {
            }
        }

        $histories = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $histories,
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
