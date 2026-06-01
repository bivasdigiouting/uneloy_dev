<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionsSettingsController extends Controller
{
    /**
     * GET payment settings
     *
     * Best-effort: infer which methods are available by looking at wallet transaction history.
     * If no history is present, default to all enabled.
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        // Best-effort inference from existing transactions.
        // We do not have explicit payment method stored in schema, so we infer from reference_type + payment_meta.
        $recent = ECardWalletTransaction::query()
            ->where('ecard_registration_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        $seen = [
            'ecard' => false,
            'wallet' => false,
            'qr' => false,
        ];

        foreach ($recent as $t) {
            $refType = strtolower((string) ($t->reference_type ?? ''));
            $txType = strtolower((string) ($t->transaction_type ?? ''));

            // For QR pay and transfers: current schema uses reference_type like `ecard_transfer`.
            // There is no separate QR method stored, but we assume QR payments create the same transfer rows.
            // We attempt to detect QR by narration/reference id hints inside payment_meta.
            $meta = $t->payment_meta;
            $metaArr = is_array($meta) ? $meta : (is_string($meta) ? json_decode($meta, true) : null);

            $narr = strtolower((string) ($t->narration ?? ''));
            $paymentHints = '';
            if (is_array($metaArr)) {
                $paymentHints = strtolower(json_encode($metaArr));
            }

            // wallet topups: transaction_type might be add
            if ($txType === 'add' || str_contains($refType, 'topup') || str_contains($narr, 'topup') || str_contains($paymentHints, 'topup')) {
                $seen['wallet'] = true;
            }

            // ecard transfers out/in
            if ($refType === 'ecard_transfer' || $txType === 'transfer_out' || $txType === 'transfer_in') {
                $seen['ecard'] = true;
            }

            // qr hints (best effort)
            if (str_contains($refType, 'qr') || str_contains($narr, 'qr') || str_contains($paymentHints, 'qr')) {
                $seen['qr'] = true;
            }
        }

        // Default: if no history for any method, enable all.
        $anySeen = ($seen['ecard'] || $seen['wallet'] || $seen['qr']);
        if (! $anySeen) {
            $seen = [
                'ecard' => true,
                'wallet' => true,
                'qr' => true,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'payment_methods' => [
                    'ecard' => (bool) $seen['ecard'],
                    'wallet' => (bool) $seen['wallet'],
                    'qr' => (bool) $seen['qr'],
                ],
                'inference' => [
                    'mode' => 'best_effort_from_wallet_transactions',
                    'notes' => 'Payment method toggles are inferred from reference_type/narration/payment_meta in ecard_wallet_transactions (no explicit method columns in current schema).',
                ],
            ],
        ]);
    }

    /**
     * POST payment settings (best-effort)
     *
     * Since current schema does not store explicit payment toggles for profile,
     * we accept the payload and respond success with no DB update.
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_methods' => 'required|array',
            'payment_methods.ecard' => 'required|boolean',
            'payment_methods.wallet' => 'required|boolean',
            'payment_methods.qr' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // No-op update (best-effort only)
        $payload = $validator->validated();

        return response()->json([
            'success' => true,
            'message' => 'Payment settings updated (best-effort, no persistence in current schema).',
            'data' => $payload,
        ]);
    }

    /**
     * GET payment shares breakdown
     *
     * Best-effort mapping:
     * - wallet: transaction_type=add (top-ups)
     * - ecard: transfer_out/in where reference_type=ecard_transfer
     * - qr: same as ecard transfer, but only if narration/meta contains qr hints
     */
    public function paymentShares(Request $request)
    {
        $user = $request->user();

        $from = $request->string('from')->toString(); // optional: YYYY-MM-DD
        $to = $request->string('to')->toString(); // optional

        $qb = ECardWalletTransaction::query()
            ->where('ecard_registration_id', $user->id);

        if ($from !== '') {
            $qb->whereDate('created_at', '>=', $from);
        }
        if ($to !== '') {
            $qb->whereDate('created_at', '<=', $to);
        }

        $rows = $qb->orderByDesc('created_at')
            ->limit(2000)
            ->get();

        $totals = [
            'ecard' => 0.0,
            'wallet' => 0.0,
            'qr' => 0.0,
        ];

        foreach ($rows as $t) {
            $refType = strtolower((string) ($t->reference_type ?? ''));
            $txType = strtolower((string) ($t->transaction_type ?? ''));
            $amount = (float) ($t->amount ?? 0);

            $meta = $t->payment_meta;
            $metaArr = is_array($meta) ? $meta : (is_string($meta) ? json_decode($meta, true) : null);
            $paymentHints = '';
            if (is_array($metaArr)) {
                $paymentHints = strtolower(json_encode($metaArr));
            }
            $narr = strtolower((string) ($t->narration ?? ''));

            // wallet top-up
            if ($txType === 'add' || str_contains($refType, 'topup') || str_contains($narr, 'topup')) {
                $totals['wallet'] += $amount;
                continue;
            }

            // ecard transfer
            if ($refType === 'ecard_transfer' || in_array($txType, ['transfer_out', 'transfer_in'], true)) {
                // detect qr hints
                $isQr = str_contains($refType, 'qr') || str_contains($narr, 'qr') || str_contains($paymentHints, 'qr');
                if ($isQr) {
                    $totals['qr'] += $amount;
                } else {
                    $totals['ecard'] += $amount;
                }
                continue;
            }
        }

        $grand = (float) ($totals['ecard'] + $totals['wallet'] + $totals['qr']);
        $shares = [
            'ecard' => $grand > 0 ? round(($totals['ecard'] / $grand) * 100, 2) : 0.0,
            'wallet' => $grand > 0 ? round(($totals['wallet'] / $grand) * 100, 2) : 0.0,
            'qr' => $grand > 0 ? round(($totals['qr'] / $grand) * 100, 2) : 0.0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'range' => [
                    'from' => $from !== '' ? $from : null,
                    'to' => $to !== '' ? $to : null,
                ],
                'mode' => 'best_effort_from_wallet_transactions',
                'totals' => [
                    'ecard' => (float) $totals['ecard'],
                    'wallet' => (float) $totals['wallet'],
                    'qr' => (float) $totals['qr'],
                    'grand_total' => (float) $grand,
                ],
                'shares_percent' => $shares,
            ],
        ]);
    }
}

