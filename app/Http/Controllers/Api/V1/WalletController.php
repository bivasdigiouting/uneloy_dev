<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Fetch Wallet Balance
     *
     * @group Wallet
     *
     * @authenticated
     *
     * Returns the current wallet balance for the logged-in customer's registration.
     * Only available when the associated registration has `department_level = customer`.
     *
     * @OA\Get(
     *     path="/api/v1/auth/wallet/balance",
     *     tags={"Wallet"},
     *     security={{"sanctum":{}}},
     *     summary="Fetch Wallet Balance",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Wallet balance fetched",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="balance", type="number", format="float", example=0.00)
     *         )
     *     ),
     *
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function getBalance(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $registration = $this->findRegistrationForUser($user);
        if (! $registration) {
            return response()->json(['success' => false, 'message' => 'Registration not found for user'], 404);
        }

        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Only customers can access wallet'], 403);
        }

        return response()->json([
            'success' => true,
            'balance' => (float) $registration->wallet_balance,
        ]);
    }

    /**
     * List Wallet Transactions
     *
     * @group Wallet
     *
     * @authenticated
     *
     * List wallet transactions for the logged-in customer. Optionally filter by type.
     * Types are `add` (credit) and `remove` (debit).
     *
     * @OA\Get(
     *     path="/api/v1/auth/wallet/transactions",
     *     tags={"Wallet"},
     *     security={{"sanctum":{}}},
     *     summary="List Wallet Transactions",
     *
     *     @OA\Parameter(name="type", in="query", required=false, @OA\Schema(type="string", enum={"add","remove"})),
     *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Transactions listed"),
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function listTransactions(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $registration = $this->findRegistrationForUser($user);
        if (! $registration) {
            return response()->json(['success' => false, 'message' => 'Registration not found for user'], 404);
        }
        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Only customers can access wallet'], 403);
        }

        $query = WalletTransaction::where('registration_id', $registration->id)->orderBy('id', 'desc');
        $type = $request->query('type');
        if ($type && in_array($type, ['add', 'remove'], true)) {
            $query->where('transaction_type', $type);
        }
        $transactions = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Create Wallet Transaction (Credit/Debit)
     *
     * @group Wallet
     *
     * @authenticated
     *
     * Create a wallet transaction for the logged-in customer. Supports `add` (credit) and `remove` (debit).
     * Prevents debit below zero.
     *
     * @OA\Post(
     *     path="/api/v1/auth/wallet/transactions",
     *     tags={"Wallet"},
     *     security={{"sanctum":{}}},
     *     summary="Create Wallet Transaction",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"type","amount"},
     *
     *             @OA\Property(property="type", type="string", enum={"add","remove","credit","debit"}, example="add"),
     *             @OA\Property(property="amount", type="number", format="float", example=100.00),
     *             @OA\Property(property="narration", type="string", example="Top-up via UPI")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Transaction created"),
     *     @OA\Response(response=422, description="Validation error or insufficient funds"),
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function createTransaction(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $registration = $this->findRegistrationForUser($user);
        if (! $registration) {
            return response()->json(['success' => false, 'message' => 'Registration not found for user'], 404);
        }
        if (strtolower((string) ($registration->department_level ?? '')) !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Only customers can access wallet'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'type' => 'required|string|in:add,remove,credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        // Normalize type to add/remove
        $type = in_array($data['type'], ['credit', 'add'], true) ? 'add' : 'remove';
        $amount = (float) $data['amount'];
        $narration = $data['narration'] ?? null;

        return DB::transaction(function () use ($registration, $user, $type, $amount, $narration) {
            $previous = (float) $registration->wallet_balance;
            if ($type === 'remove' && $previous < $amount) {
                return response()->json(['success' => false, 'message' => 'Insufficient funds'], 422);
            }

            $newBalance = $type === 'add' ? ($previous + $amount) : ($previous - $amount);
            $registration->wallet_balance = $newBalance;
            $registration->save();

            $tx = WalletTransaction::create([
                'registration_id' => $registration->id,
                'transaction_type' => $type,
                'amount' => $amount,
                'previous_balance' => $previous,
                'new_balance' => $newBalance,
                'narration' => $narration,
                'performed_by_user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction recorded',
                'data' => $tx,
                'balance' => $newBalance,
            ], 201);
        });
    }

    /**
     * Add Wallet Balance (Convenience)
     *
     * @group Wallet
     *
     * @authenticated
     *
     * Shortcut to add funds to wallet; creates a credit transaction.
     *
     * @OA\Post(
     *     path="/api/v1/auth/wallet/balance/add",
     *     tags={"Wallet"},
     *     security={{"sanctum":{}}},
     *     summary="Add Wallet Balance",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"amount"},
     *
     *             @OA\Property(property="amount", type="number", format="float", example=50.00),
     *             @OA\Property(property="narration", type="string", example="Promo credit")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Balance added"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Registration not found"),
     *     @OA\Response(response=403, description="Not allowed for non-customer department_level")
     * )
     */
    public function addBalance(Request $request)
    {
        $request->merge(['type' => 'add']);

        return $this->createTransaction($request);
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
