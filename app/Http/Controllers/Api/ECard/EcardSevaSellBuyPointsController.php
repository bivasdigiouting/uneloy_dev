<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\ECardSevaOtherPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcardSevaSellBuyPointsController extends Controller
{
    private function parseDate(?string $date): ?\Carbon\Carbon
    {
        if (! $date) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Sell/Buy Points report breakdown (ECard Seva)
     *
     * Best-effort API for the "sell buy points" report.
     * Current data source: ecard_seva_other_points
     *
     * sell  = send_points (points being sent)
     * buy   = points (points being requested)
     *
     * status filters supported for both request and send lifecycle.
     */
    public function breakdown(Request $request)
    {
        $user = $request->user();

        // In portal context, other_points rows are typically associated with the portal via parent_id.
        // But this table model does not explicitly define portal mapping in controller.
        // We'll use best-effort filtering on created/approved fields if available.

        $from = $this->parseDate($request->string('from')->toString());
        $to = $this->parseDate($request->string('to')->toString());

        if ($from && $to) {
            $from = $from->startOfDay();
            $to = $to->endOfDay();
        } else {
            // default last 30 days
            $to = \Carbon\Carbon::now()->endOfDay();
            $from = (clone $to)->subDays(30)->startOfDay();
        }

        $range = $request->string('range', '30d')->toString();
        if ($request->filled('range')) {
            $now = \Carbon\Carbon::now();
            $from = match ($range) {
                '7d' => $now->copy()->subDays(6)->startOfDay(),
                '90d' => $now->copy()->subDays(89)->startOfDay(),
                '12m' => $now->copy()->subMonths(11)->startOfMonth(),
                'y' => $now->copy()->startOfYear(),
                default => $now->copy()->subDays(29)->startOfDay(),
            };
            $to = $now->endOfDay();
        }

        $groupBy = $request->string('group_by', 'month')->toString();
        // allowed: day|month|year
        $groupBy = strtolower($groupBy);
        if (! in_array($groupBy, ['day', 'month', 'year'], true)) {
            $groupBy = 'month';
        }

        $status = $request->string('status', '')->toString(); // optional
        $status = $status !== '' ? $status : null;

        $dateField = match ($groupBy) {
            'day' => "DATE(request_date)",
            'month' => "DATE_FORMAT(request_date, '%Y-%m')",
            'year' => "DATE_FORMAT(request_date, '%Y')",
        };

        // Breakdown for each period:
        // - buy_points_sum  : SUM(points)
        // - sell_points_sum : SUM(send_points)
        // Use COALESCE to avoid null sums.

        $rows = DB::table('ecard_seva_other_points')
            ->selectRaw("{$dateField} as period_key")
            ->selectRaw('COALESCE(SUM(points), 0) as buy_points')
            ->selectRaw('COALESCE(SUM(send_points), 0) as sell_points')
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->whereBetween('request_date', [$from, $to])
            ->groupBy('period_key')
            ->orderBy('period_key')
            ->get();

        $labels = [];
        $buy = [];
        $sell = [];

        foreach ($rows as $r) {
            $labels[] = (string) $r->period_key;
            $buy[] = (float) ($r->buy_points ?? 0);
            $sell[] = (float) ($r->sell_points ?? 0);
        }

        $totals = [
            'buy_points' => array_sum($buy),
            'sell_points' => array_sum($sell),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'range' => [
                    'from' => $from->toDateTimeString(),
                    'to' => $to->toDateTimeString(),
                    'group_by' => $groupBy,
                ],
                'totals' => [
                    'buy_points' => (float) $totals['buy_points'],
                    'sell_points' => (float) $totals['sell_points'],
                    'currency' => 'INR',
                ],
                'report' => [
                    'labels' => $labels,
                    'buy_points' => $buy,
                    'sell_points' => $sell,
                ],
                'notes' => [
                    'buy_points' => 'points column (buy/request) from ecard_seva_other_points',
                    'sell_points' => 'send_points column (sell/send) from ecard_seva_other_points',
                ],
            ],
        ]);
    }

    /**
     * Simple list breakdown (optional helper)
     */
    public function list(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:200',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $user = $request->user();
        $limit = (int) ($request->integer('limit', 50));
        $limit = max(1, min($limit, 200));

        $from = $this->parseDate($request->string('from')->toString());
        $to = $this->parseDate($request->string('to')->toString());
        if ($from && $to) {
            $from = $from->startOfDay();
            $to = $to->endOfDay();
        } else {
            $to = \Carbon\Carbon::now()->endOfDay();
            $from = (clone $to)->subDays(30)->startOfDay();
        }

        $rows = ECardSevaOtherPoint::query()
            ->whereBetween('request_date', [$from, $to])
            ->orderByDesc('request_date')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'items' => $rows->map(function (ECardSevaOtherPoint $r) {
                    return [
                        'id' => (int) $r->id,
                        'name' => (string) ($r->name ?? '-'),
                        'mobile_no' => (string) ($r->mobile_no ?? ''),
                        'request_date' => $r->request_date?->toDateTimeString(),
                        'points' => (int) ($r->points ?? 0),
                        'status' => $r->status !== null ? (string) $r->status : null,
                        'send_points' => $r->send_points !== null ? (int) $r->send_points : null,
                        'send_points_date' => $r->send_points_date?->toDateTimeString(),
                        'remarks' => $r->remarks !== null ? (string) $r->remarks : null,
                    ];
                })->values(),
            ],
        ]);
    }
}

