<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardRegistration;
use App\Models\ECardWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayTabController extends Controller
{
    /**
     * Current balance (Pay tab)
     */
    public function balance(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'wallet_balance' => (float) ($user->wallet_balance ?? 0),
            'currency' => 'INR',
        ]);
    }

    /**
     * Pay with QR (QR encodes receiver identity)
     *
     * Input:
     * - qr: string (recommended) OR to_ecard_registration_id
     * - amount: number
     *
     * Output:
     * - transaction_reference_id
     * - new_balance
     */
    public function payWithQr(Request $request)
    {
        $request->validate([
            'qr' => 'nullable|string',
            'to_ecard_registration_id' => 'nullable|integer|min:1',
            'amount' => 'required|numeric|min:1',
            'narration' => 'nullable|string|max:255',
        ]);

        $toId = $request->integer('to_ecard_registration_id');
        $qr = (string) ($request->qr ?? '');

        // If qr is provided and toId is missing, interpret qr as:
        // - a plain integer id
        // - or a string like "ecard:123" / "123".
        if (! $toId && $qr !== '') {
            $toId = $this->extractEcardIdFromQr($qr);
        }

        if (! $toId) {
            return response()->json(['message' => 'Invalid QR: receiver id not found'], 422);
        }

        return $this->transfer($request, $toId);
    }

    /**
     * E-Card transfer amount (user-to-user)
     */
    public function transfer(Request $request, ?int $toId = null)
    {
        $request->validate([
            'to_ecard_registration_id' => 'nullable|integer|min:1',
            'amount' => 'required|numeric|min:1',
            'narration' => 'nullable|string|max:255',
        ]);

        $toId = $toId ?? $request->integer('to_ecard_registration_id');
        $amount = (float) $request->amount;
        $narration = $request->filled('narration') ? (string) $request->narration : null;

        $payer = $request->user();

        if ((int) $payer->id === (int) $toId) {
            return response()->json(['message' => 'Cannot transfer to yourself'], 422);
        }

        $receiver = ECardRegistration::query()->find($toId);
        if (! $receiver) {
            return response()->json(['message' => 'Receiver not found'], 404);
        }

        if ((float) ($payer->wallet_balance ?? 0) < $amount) {
            return response()->json(['message' => 'Insufficient wallet balance'], 422);
        }

        $referenceId = 'EC_TRX_' . Str::upper(Str::random(14));
        $referenceType = 'ecard_transfer';

        $result = DB::transaction(function () use ($payer, $receiver, $amount, $narration, $referenceId, $referenceType) {
            // Lock both accounts to prevent race conditions.
            $payerLocked = ECardRegistration::query()->lockForUpdate()->find($payer->id);
            $receiverLocked = ECardRegistration::query()->lockForUpdate()->find($receiver->id);

            $previousPayer = (float) ($payerLocked->wallet_balance ?? 0);
            $newPayer = $previousPayer - $amount;

            if ($newPayer < 0) {
                return ['status' => 'failed', 'message' => 'Insufficient balance'];
            }

            $previousReceiver = (float) ($receiverLocked->wallet_balance ?? 0);
            $newReceiver = $previousReceiver + $amount;

            $payerLocked->wallet_balance = $newPayer;
            $receiverLocked->wallet_balance = $newReceiver;
            $payerLocked->save();
            $receiverLocked->save();

            $debitNarration = $narration ?? ('Transfer sent to ecard #' . $receiverLocked->id);
            $creditNarration = $narration ?? ('Transfer received from ecard #' . $payerLocked->id);

            ECardWalletTransaction::create([
                'ecard_registration_id' => $payerLocked->id,
                'transaction_type' => 'transfer_out',
                'amount' => $amount,
                'previous_balance' => $previousPayer,
                'new_balance' => $newPayer,
                'narration' => $debitNarration,
                'performed_by_id' => $payerLocked->id,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'gateway_transaction_id' => null,
                'gateway_name' => null,
                'payment_status' => 'success',
                'payment_meta' => [
                    'counterparty_ecard_registration_id' => (int) $receiverLocked->id,
                    'direction' => 'out',
                ],
            ]);

            ECardWalletTransaction::create([
                'ecard_registration_id' => $receiverLocked->id,
                'transaction_type' => 'transfer_in',
                'amount' => $amount,
                'previous_balance' => $previousReceiver,
                'new_balance' => $newReceiver,
                'narration' => $creditNarration,
                'performed_by_id' => $payerLocked->id,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'gateway_transaction_id' => null,
                'gateway_name' => null,
                'payment_status' => 'success',
                'payment_meta' => [
                    'counterparty_ecard_registration_id' => (int) $payerLocked->id,
                    'direction' => 'in',
                ],
            ]);

            return [
                'status' => 'success',
                'reference_id' => $referenceId,
                'payer_new_balance' => $newPayer,
                'receiver_new_balance' => $newReceiver,
            ];
        });

        if (($result['status'] ?? '') !== 'success') {
            return response()->json(['message' => $result['message'] ?? 'Transfer failed'], 400);
        }

        return response()->json([
            'message' => 'Transfer successful',
            'status' => 'success',
            'transaction_reference_id' => $result['reference_id'],
            'payer' => [
                'wallet_balance' => (float) $result['payer_new_balance'],
            ],
            'receiver' => [
                'wallet_balance' => (float) $result['receiver_new_balance'],
            ],
        ]);
    }

    /**
     * Transaction history - transaction details (wallet table only)
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        $limit = (int) $request->integer('limit', 20);
        $limit = max(1, min($limit, 200));

        $type = $request->string('type')->toString(); // all|in|out
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $query = ECardWalletTransaction::query()
            ->where('ecard_registration_id', $user->id);

        if ($type !== '' && strtolower($type) !== 'all') {
            if (in_array(strtolower($type), ['out', 'debit'], true)) {
                $query->where('transaction_type', 'transfer_out');
            } elseif (in_array(strtolower($type), ['in', 'credit'], true)) {
                $query->where('transaction_type', 'transfer_in');
            }
        }

        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }

        $rows = $query->orderByDesc('created_at')->limit($limit)->get();

        $items = $rows->map(function (ECardWalletTransaction $t) {
            $meta = is_array($t->payment_meta) ? $t->payment_meta : (is_string($t->payment_meta) ? json_decode($t->payment_meta, true) : null);
            $direction = match ((string) $t->transaction_type) {
                'transfer_out' => 'out',
                'transfer_in' => 'in',
                default => 'unknown',
            };

            $counterpartyId = $meta['counterparty_ecard_registration_id'] ?? null;
            $counterpartyName = null;

            if ($counterpartyId) {
                $counterpartyName = ECardRegistration::query()
                    ->where('id', (int) $counterpartyId)
                    ->value(DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name)"));
            }

            return [
                'id' => (int) $t->id,
                'created_at' => $t->created_at?->toDateTimeString(),
                'transaction_type' => (string) $t->transaction_type,
                'direction' => $direction,
                'amount' => (float) $t->amount,
                'previous_balance' => (float) $t->previous_balance,
                'new_balance' => (float) $t->new_balance,
                'narration' => (string) ($t->narration ?? ''),
                'status' => (string) ($t->payment_status ?? ''),
                'reference' => [
                    'reference_type' => (string) ($t->reference_type ?? ''),
                    'reference_id' => (string) ($t->reference_id ?? ''),
                ],
                'counterparty' => [
                    'ecard_registration_id' => $counterpartyId !== null ? (int) $counterpartyId : null,
                    'name' => $counterpartyName !== null ? (string) $counterpartyName : null,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $items->count(),
                'items' => $items->values(),
            ],
        ]);
    }

    /**
     * Monthly outflow breakdown for graph.
     *
     * Outflow = transfer_out where ecard_registration_id = current user.
     */
    public function monthlyOutflow(Request $request)
    {
        $user = $request->user();

        $year = (int) $request->integer('year', now()->year);

        $rows = ECardWalletTransaction::query()
            ->where('ecard_registration_id', $user->id)
            ->where('transaction_type', 'transfer_out')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month_no, SUM(amount) as total_outflow')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('month_no asc')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $monthNo = (int) ($r->month_no ?? 0);
            $monthLabel = date('M', mktime(0, 0, 0, $monthNo, 1, $year));
            $map[$monthLabel] = (float) ($r->total_outflow ?? 0);
        }

        // Ensure 12 months
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $label = date('M', mktime(0, 0, 0, $m, 1, $year));
            $result[] = [
                'month' => $label,
                'total_outflow' => (float) ($map[$label] ?? 0),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year,
                'items' => $result,
            ],
        ]);
    }

    private function extractEcardIdFromQr(string $qr): ?int
    {
        $qr = trim($qr);
        if ($qr === '') {
            return null;
        }

        // Plain integer
        if (ctype_digit($qr)) {
            return (int) $qr;
        }

        // Try patterns like "ecard:123" or "id=123" or "upi://..." (we take first number group)
        if (preg_match('/(ecard:|id=|to=|user=)?(\\d+)/i', $qr, $m)) {
            return isset($m[2]) && ctype_digit($m[2]) ? (int) $m[2] : null;
        }

        return null;
    }
}

