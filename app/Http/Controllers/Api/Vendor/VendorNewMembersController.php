<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ECardRegistration;
use App\Models\Registration;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorNewMembersController extends Controller
{
    private function getVendorId(Request $request): int
    {
        $user = Auth::guard('sanctum')->user();
        return (int) ($user?->id ?? 0);
    }

    private function parseDate(?string $date): ?Carbon
    {
        if (! $date) {
            return null;
        }
        try {
            return Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function earningsRangeDates(string $range): array
    {
        $to = Carbon::now();
        return match ($range) {
            '7d' => [$to->copy()->subDays(6)->startOfDay(), $to->endOfDay()],
            '90d' => [$to->copy()->subDays(89)->startOfDay(), $to->endOfDay()],
            '12m' => [$to->copy()->subMonths(11)->startOfMonth(), $to->endOfDay()],
            'y' => [$to->copy()->startOfYear(), $to->endOfDay()],
            default => [$to->copy()->subDays(29)->startOfDay(), $to->endOfDay()], // 30d
        };
    }

    private function normalizeRange(string $range): string
    {
        return match (strtolower(trim($range))) {
            'daily' => '7d',
            'monthly' => '12m',
            'yearly' => 'y',
            default => strtolower(trim($range)),
        };
    }

    private function periodKeySelect(string $normalizedRange): string
    {
        $isMonthly = in_array($normalizedRange, ['12m'], true);
        $isYearly = in_array($normalizedRange, ['y'], true);

        return match (true) {
            $isYearly => "DATE_FORMAT(created_at, '%Y')",
            $isMonthly => "DATE_FORMAT(created_at, '%Y-%m')",
            default => "DATE(created_at)",
        };
    }

    /**
     * Vendor New Members graph breakdown
     *
     * Inputs:
     * - range: today|monthly|yearly|daily|30d|90d|12m|y (implementation normalizes)
     * - date: optional selected date (YYYY-MM-DD)
     *
     * Output format aligns with ECard SevaMembersController::report
     */
    public function report(Request $request)
    {
        $vendorId = $this->getVendorId($request);

        $range = $request->string('range', '30d')->toString();
        $selectedDate = $request->string('date', '')->toString();

        // Internal mapping
        $normalizedRange = match (strtolower($range)) {
            'today' => '7d', // closest supported
            default => $this->normalizeRange($range),
        };

        [$from, $to] = $this->earningsRangeDates($normalizedRange);

        $selectPeriod = $this->periodKeySelect($normalizedRange);
        $isMonthlyGrouping = in_array($normalizedRange, ['12m'], true);
        $isYearlyGrouping = in_array($normalizedRange, ['y'], true);

        // registrations
        $reg = DB::table('registrations as r')
            ->select([
                DB::raw($selectPeriod . ' as period_key'),
                DB::raw('COUNT(*) as reg_count'),
            ])
            ->where('r.parent_id', $vendorId)
            ->whereBetween('r.created_at', [$from, $to])
            ->groupBy('period_key');

        // ecard registrations
        $ecard = DB::table('ecard_registrations as e')
            ->select([
                DB::raw($selectPeriod . ' as period_key'),
                DB::raw('COUNT(*) as ecard_count'),
            ])
            ->where('e.parent_id', $vendorId)
            ->whereBetween('e.created_at', [$from, $to])
            ->groupBy('period_key');

        $rows = DB::query()
            ->fromSub($reg, 'a')
            ->leftJoinSub($ecard, 'b', function ($join) {
                $join->on('a.period_key', '=', 'b.period_key');
            })
            ->select([
                'a.period_key',
                DB::raw('COALESCE(a.reg_count, 0) as reg_count'),
                DB::raw('COALESCE(b.ecard_count, 0) as ecard_count'),
            ])
            ->orderBy('a.period_key')
            ->get();

        $labels = [];
        $regValues = [];
        $ecardValues = [];

        foreach ($rows as $r) {
            $labels[] = (string) $r->period_key;
            $regValues[] = (int) $r->reg_count;
            $ecardValues[] = (int) $r->ecard_count;
        }

        if (! empty($selectedDate)) {
            $sd = $this->parseDate($selectedDate);
            if ($sd) {
                $key = match (true) {
                    $isYearlyGrouping => $sd->format('Y'),
                    $isMonthlyGrouping => $sd->format('Y-m'),
                    default => $sd->toDateString(),
                };

                if (! in_array($key, $labels, true)) {
                    $labels[] = $key;
                    $regValues[] = 0;
                    $ecardValues[] = 0;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'range' => $range,
                'labels' => $labels,
                'registrationCounts' => $regValues,
                'ecardCounts' => $ecardValues,
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Vendor New Members totals
     *
     * Inputs:
     * - from, to: optional
     */
    public function summary(Request $request)
    {
        $vendorId = $this->getVendorId($request);

        $fromDate = $this->parseDate($request->input('from'));
        $toDate = $this->parseDate($request->input('to'));
        $now = Carbon::now();

        $from = $fromDate ? $fromDate->startOfDay() : $now->copy()->subDays(30)->startOfDay();
        $to = $toDate ? $toDate->endOfDay() : $now->copy()->endOfDay();

        $regQ = DB::table('registrations as r')->where('r.parent_id', $vendorId);
        $ecardQ = DB::table('ecard_registrations as e')->where('e.parent_id', $vendorId);

        if ($request->filled('from') && $request->filled('to')) {
            $regQ->whereBetween('r.created_at', [$from, $to]);
            $ecardQ->whereBetween('e.created_at', [$from, $to]);
        }

        $totalRegistrations = (int) $regQ->count();
        $ecardSeva = (int) $ecardQ->count();

        $activeUser = (int) $ecardQ
            ->cloneWithoutBindings()
            ->where(function ($q) {
                $q->whereRaw('LOWER(e.status) = ?', ['active'])
                  ->orWhere('e.status', 1);
            })
            ->count();

        // Vendors added in the system is not vendor-scoped, but we keep same keys.
        $vendorsAdded = (int) Vendor::query()
            ->when($request->filled('from') && $request->filled('to'), function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'currency' => 'INR',
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'totalRegistrations' => $totalRegistrations,
                'ecardSeva' => $ecardSeva,
                'activeUser' => $activeUser,
                'vendorsAdded' => $vendorsAdded,
            ],
        ]);
    }

    /**
     * Vendor New Members list
     *
     * Inputs:
     * - from,to (optional) OR range/date
     * - type: user|ecard|all (default all)
     * - limit: 1..200
     */
    public function list(Request $request)
    {
        $vendorId = $this->getVendorId($request);

        $from = $this->parseDate($request->string('from')->toString())?->startOfDay() ?? Carbon::now()->subDays(30)->startOfDay();
        $to = $this->parseDate($request->string('to')->toString())?->endOfDay() ?? Carbon::now()->endOfDay();

        $limit = (int) $request->integer('limit', 50);
        $limit = max(1, min($limit, 200));

        $range = $request->string('range', '30d')->toString();
        $selectedDate = $request->string('date', '')->toString();

        $fromParam = $request->string('from')->toString();
        $toParam = $request->string('to')->toString();

        if ($request->filled('from') && $request->filled('to')) {
            $from = $this->parseDate($fromParam)?->startOfDay() ?? Carbon::now()->subDays(30)->startOfDay();
            $to = $this->parseDate($toParam)?->endOfDay() ?? Carbon::now()->endOfDay();
        } else {
            $normalizedRange = match (strtolower($range)) {
                'today' => '7d',
                default => $this->normalizeRange($range),
            };

            [$from, $to] = $this->earningsRangeDates($normalizedRange);

            $sd = $this->parseDate($selectedDate ?: '');
            if ($sd && empty($fromParam) && empty($toParam)) {
                $from = $sd->startOfDay();
                $to = $sd->endOfDay();
            }
        }

        $type = $request->string('type', 'all')->toString();

        $rows = collect();

        if (in_array($type, ['all', 'user'], true)) {
            $rows = $rows->merge(
                DB::table('registrations as r')
                    ->where('r.parent_id', $vendorId)
                    ->whereBetween('r.created_at', [$from, $to])
                    ->select([
                        DB::raw("'user' as member_type"),
                        'r.id as entity_id',
                        DB::raw("CONCAT_WS(' ', r.first_name, r.middle_name, r.last_name) as name"),
                        DB::raw('NULL as red_id'),
                        'r.created_at as created_at',
                    ])->get()
            );
        }

        if (in_array($type, ['all', 'ecard'], true)) {
            $rows = $rows->merge(
                DB::table('ecard_registrations as e')
                    ->where('e.parent_id', $vendorId)
                    ->whereBetween('e.created_at', [$from, $to])
                    ->select([
                        DB::raw("'ecard' as member_type"),
                        'e.id as entity_id',
                        DB::raw("CONCAT_WS(' ', e.first_name, e.middle_name, e.last_name) as name"),
                        DB::raw('COALESCE(e.member_id, e.id) as red_id'),
                        'e.created_at as created_at',
                    ])->get()
            );
        }

        // Vendor "vendor" type is intentionally not included (not clearly parent-scoped in current schema).

        $data = $rows
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->map(function ($r) {
                return [
                    'memberType' => $r->member_type,
                    'entityId' => (int) $r->entity_id,
                    'name' => (string) ($r->name ?? '-'),
                    'redId' => $r->red_id !== null ? (string) $r->red_id : null,
                    'createdAt' => $r->created_at?->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'items' => $data,
            ],
        ]);
    }
}

