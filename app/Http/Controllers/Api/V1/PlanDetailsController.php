<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanDetailsController extends Controller
{
    protected SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Operator plan fetch via third-party API.
     *
     * Calls upstream `{baseUrl}/verification/operator_plan_fetch` with credentials
     * and the provided `mobile`, `opcode`, and `circle`.
     *
     * @group Third Party Verification
     *
     * @unauthenticated
     *
     * @queryParam mobile string required The 10-digit mobile number. Example: 9876543210.
     * @queryParam opcode string required The operator code obtained from operator-find endpoint. Example: VODA.
     * @queryParam circle string required The circle code obtained from operator-find endpoint. Example: DEL.
     * @queryParam order_id string The 10-digit order ID. If not provided, a random 10-digit ID will be generated.
     *
     * @response 200 {
     *   "success": true,
     *   "order_id": "1234567890",
     *   "data": {"plans": []}
     * }
     * @response 400 {"success": false, "message": "Validation error."}
     * @response 500 {"success": false, "message": "Upstream request failed."}
     */
    public function fetch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'digits:10'],
            'opcode' => ['required', 'string'],
            'circle' => ['required', 'string'],
            'order_id' => ['nullable', 'digits:10'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?? 'Validation error.',
            ], 400);
        }

        $mobile = $request->query('mobile');
        $opcode = $request->query('opcode');
        $circle = $request->query('circle');
        $orderId = $request->query('order_id') ?: (string) random_int(1000000000, 9999999999);

        $settings = $this->settingsRepository->getSettings();
        $baseUrl = rtrim($settings->third_party_api_url ?? '', '/');
        $username = $settings->third_party_api_username ?? null;
        $token = $settings->third_party_api_token ?? null;

        if (! $baseUrl || ! $username || ! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Third-party API settings are not configured.',
            ], 500);
        }

        $endpoint = $baseUrl.'/verification/operator_plan_fetch';
        $query = http_build_query([
            'username' => $username,
            'token' => $token,
            'mobile' => $mobile,
            'opcode' => $opcode,
            'circle' => $circle,
            'orderid' => $orderId,
        ]);

        $url = $endpoint.'?'.$query;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $responseBody = curl_exec($ch);
        $curlError = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($responseBody === false) {
            return response()->json([
                'success' => false,
                'message' => 'Upstream request failed: '.($curlError ?: 'Unknown cURL error'),
            ], 500);
        }

        $decoded = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => $statusCode >= 200 && $statusCode < 300,
                'order_id' => $orderId,
                'data' => null,
                'raw' => $responseBody,
            ], $statusCode ?: 200);
        }

        return response()->json([
            'success' => $statusCode >= 200 && $statusCode < 300,
            'order_id' => $orderId,
            'data' => $decoded,
        ], $statusCode ?: 200);
    }
}
