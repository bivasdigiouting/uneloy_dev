<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardWalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SevaIncomeController extends Controller
{
    private function getEcardRegistrationId(Request $request): int
    {
        $user = Auth::guard('sanctum')->user();
        // In this codebase ECardRegistration uses sanctum tokens (HasApiTokens).
        return (int) ($user?->id ?? 0);
    }

    private function parseDate(string $date): ?Carbon
    {
        try {
            return Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function formatMoney($value): float
    {
        return (float) $value;
    }

    // 1) daily/monthly/yearly/total earnings
    public function summary(Request $request)
    {
        $registrationId = $this->getEcardRegistrationId($request);

        $now = Carbon::now();
        $dailyFrom = $now->copy()->startOfDay();
        $monthlyFrom = $now->copy()->startOfMonth();
        $yearlyFrom = $now->copy()->startOfYear();

        // We consider “earnings” as wallet credits into the ecard wallet.
        // If you have a more specific seva narration/type later, we can refine here.
        $base = ECardWalletTransaction::query()
            ->where('ecard_registration_id', $registrationId)
            ->where('transaction_type', 'add');

        $daily = (clone $base)->where('created_at', '>=', $dailyFrom)->sum('amount');
        $monthly = (clone $base)->where('created_at', '>=', $monthlyFrom)->sum('amount');
        $yearly = (clone $base)->where('created_at', '>=', $yearlyFrom)->sum('amount');
        $total = (clone $base)->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'dailyEarnings' => $this->formatMoney($daily),
                'monthlyEarnings' => $this->formatMoney($monthly),
                'yearlyEarnings' => $this->formatMoney($yearly),
                'totalEarnings' => $this->formatMoney($total),
                'currency' => 'INR',
                'asOf' => $now->toDateTimeString(),
            ],
        ]);
    }

    // 2) periodic values for the graph (selected date earnings included)
    public function graph(Request $request)
    {
        $registrationId = $this->getEcardRegistrationId($request);

        $range = $request->string('range', '30d')->toString(); // 7d|30d|90d|12m|y
        $selectedDate = $request->string('date')->toString(); // optional

        $to = Carbon::now();
        $from = match ($range) {
            '7d' => $to->copy()->subDays(6)->startOfDay(),
            '90d' => $to->copy()->subDays(89)->startOfDay(),
            '12m' => $to->copy()->subMonths(11)->startOfMonth(),
            'y' => $to->copy()->startOfYear(),
            default => $to->copy()->subDays(29)->startOfDay(), // 30d
        };

        $isMonthlyGrouping = in_array($range, ['12m'], true);

        // Labels + aggregation
        $query = ECardWalletTransaction::query()
            ->select([
                DB::raw($isMonthlyGrouping
                    ? "DATE_FORMAT(created_at, '%Y-%m')"
                    : "DATE(created_at)"
                ) . ' as period_key',
                DB::raw('SUM(amount) as total_amount'),
            ])
            ->where('ecard_registration_id', $registrationId)
            ->where('transaction_type', 'add')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('period_key')
            ->orderBy('period_key');

        $rows = $query->get();

        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $labels[] = (string) $row->period_key;
            $values[] = (float) $row->total_amount;
        }

        // Ensure selected date exists in output if provided
        if ($selectedDate) {
            $sd = $this->parseDate($selectedDate);
            if ($sd) {
                $key = $isMonthlyGrouping ? $sd->format('Y-m') : $sd->toDateString();
                if (!in_array($key, $labels, true)) {
                    // Add it with 0 earnings
                    $labels[] = $key;
                    $values[] = 0.0;

                    // keep stable ordering
                    array_multisort($labels, SORT_ASC, $values);
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range,
                'labels' => $labels,
                'values' => $values,
                'currency' => 'INR',
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
            ],
        ]);
    }

    // 3) list of income rows
    public function list(Request $request)
    {
        $registrationId = $this->getEcardRegistrationId($request);

        $period = $request->string('period', 'date')->toString(); // daily|monthly|yearly|date
        $date = $request->string('date')->toString();
        $limit = (int) $request->integer('limit', 50);
        $limit = max(1, min($limit, 200));

        $qb = ECardWalletTransaction::query()
            ->with('registration')
            ->where('ecard_registration_id', $registrationId)
            ->where('transaction_type', 'add')
            ->orderByDesc('created_at');

        if ($period === 'daily') {
            $d = $this->parseDate($date ?: Carbon::now()->toDateString());
            if ($d) {
                $qb->whereDate('created_at', $d->toDateString());
            }
        } elseif ($period === 'monthly') {
            $d = $this->parseDate($date ?: Carbon::now()->toDateString());
            if ($d) {
                $qb->whereBetween('created_at', [$d->copy()->startOfMonth(), $d->copy()->endOfMonth()]);
            }
        } elseif ($period === 'yearly') {
            $d = $this->parseDate($date ?: Carbon::now()->toDateString());
            if ($d) {
                $qb->whereBetween('created_at', [$d->copy()->startOfYear(), $d->copy()->endOfYear()]);
            }
        } else {
            // selected date earnings (default)
            $d = $this->parseDate($date ?: Carbon::now()->toDateString());
            if ($d) {
                $qb->whereDate('created_at', $d->toDateString());
            }
        }

        $rows = $qb->take($limit)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'date' => $date ?: Carbon::now()->toDateString(),
                'items' => $rows->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'amount' => (float) $r->amount,
                        'narration' => $r->narration,
                        'reference_type' => $r->reference_type,
                        'reference_id' => $r->reference_id,
                        'gateway_transaction_id' => $r->gateway_transaction_id ?? null,
                        'gateway_name' => $r->gateway_name ?? null,
                        'payment_status' => $r->payment_status ?? null,
                        'created_at' => $r->created_at?->toDateTimeString(),
                    ];
                }),
            ],
        ]);
    }

    // 4) income source breakdown
    public function sources(Request $request)
    {
        $registrationId = $this->getEcardRegistrationId($request);

        $from = $this->parseDate($request->string('from')->toString());
        $to = $this->parseDate($request->string('to')->toString());

        $now = Carbon::now();
        $from = $from ?? $now->copy()->subDays(30)->startOfDay();
        $to = $to ?? $now->copy()->endOfDay();

        $rows = ECardWalletTransaction::query()
            ->select([
                DB::raw('COALESCE(reference_type, ) as source_key'),
                DB::raw('SUM(amount) as total_amount'),
            ])
            ->where('ecard_registration_id', $registrationId)
            ->where('transaction_type', 'add')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('source_key')
            ->orderByDesc('total_amount')
            ->get();

        $items = $rows->map(function ($r) {
            return [
                'source' => (string) $r->source_key,
                'amount' => (float) $r->total_amount,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'currency' => 'INR',
                'sources' => $items,
            ],
        ]);
    }
}

