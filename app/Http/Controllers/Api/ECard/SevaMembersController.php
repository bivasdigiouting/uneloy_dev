<?php

namespace App\Http\Controllers\Api\ECard;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SevaMembersController extends Controller
{
    private function getPortalEcardId(Request $request): int
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

    /**
     * 1) members report periodic values
     * Uses counts of:
     * - registrations (type: user)
     * - ecard_registrations (type: ecard)
     * Filtered by portal user's parent_id.
     */
    public function report(Request $request)
    {
        $portalEcardId = $this->getPortalEcardId($request);

        $range = $request->string('range', '30d')->toString(); // 7d|30d|90d|12m|y|daily|monthly|yearly
        $selectedDate = $request->string('date', '')->toString();

        // Normalize supported values to internal range keys
        $normalizedRange = match ($range) {
            'daily' => '7d',
            'monthly' => '12m',
            'yearly' => 'y',
            default => $range,
        };

        // For daily graph use DATE(created_at). For monthly/yearly use month/year grouping.
        $isMonthlyGrouping = in_array($normalizedRange, ['12m'], true);
        $isYearlyGrouping = in_array($normalizedRange, ['y'], true);

        [$from, $to] = $this->earningsRangeDates($normalizedRange);

        $selectPeriod = match (true) {
            $isYearlyGrouping => "DATE_FORMAT(created_at, '%Y')",
            $isMonthlyGrouping => "DATE_FORMAT(created_at, '%Y-%m')",
            default => "DATE(created_at)",
        };

        // registrations
        $reg = DB::table('registrations as r')
            ->select([
                DB::raw($selectPeriod . ' as period_key'),
                DB::raw('COUNT(*) as reg_count'),
            ])
            ->where('r.parent_id', $portalEcardId)
            ->whereBetween('r.created_at', [$from, $to])
            ->groupBy('period_key');

        // ecard registrations
        $ecard = DB::table('ecard_registrations as e')
            ->select([
                DB::raw($selectPeriod . ' as period_key'),
                DB::raw('COUNT(*) as ecard_count'),
            ])
            ->where('e.parent_id', $portalEcardId)
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
     * 2) total registration values: total registrations, ecard seva, active user, vendors added
     */
    public function summary(Request $request)
    {
        $portalEcardId = $this->getPortalEcardId($request);

        $fromDate = $this->parseDate($request->input('from'));
        $toDate = $this->parseDate($request->input('to'));
        $now = Carbon::now();

        $from = $fromDate ? $fromDate->startOfDay() : $now->copy()->subDays(30)->startOfDay();
        $to = $toDate ? $toDate->endOfDay() : $now->copy()->endOfDay();

        $regQ = DB::table('registrations as r')->where('r.parent_id', $portalEcardId);
        $ecardQ = DB::table('ecard_registrations as e')->where('e.parent_id', $portalEcardId);

        if ($request->filled('from') && $request->filled('to')) {
            $regQ->whereBetween('r.created_at', [$from, $to]);
            $ecardQ->whereBetween('e.created_at', [$from, $to]);
        }

        $totalRegistrations = (int) $regQ->count();
        $ecardSeva = (int) $ecardQ->count();

        $activeUser = (int) $ecardQ
            ->cloneWithoutBindings()
            ->where(function ($q) {
                // robust status matching
                $q->whereRaw('LOWER(e.status) = ?', ['active'])
                  ->orWhere('e.status', 1);
            })
            ->count();

        // Vendors table: no clear relation to ecard seva in existing code; so we return total vendors in system.
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
     * 3) list vendor/ecard/user registrations
     * Returns unified rows (vendor/ecard/registration).
     * Red id is treated as ecard_registrations.member_id if exists, otherwise ecard_registrations.id.
     */
    public function list(Request $request)
    {
        $portalEcardId = $this->getPortalEcardId($request);

        $from = $this->parseDate($request->string('from')->toString())?->startOfDay() ?? Carbon::now()->subDays(30)->startOfDay();
        $to = $this->parseDate($request->string('to')->toString())?->endOfDay() ?? Carbon::now()->endOfDay();
        $limit = (int) $request->integer('limit', 50);
        $limit = max(1, min($limit, 200));

        $range = $request->string('range', '30d')->toString();
        $selectedDate = $request->string('date', '')->toString();


        // Support both explicit date range (from/to) and period keys (range/date)
        // - from/to: {from, to} take priority
        // - range/date: if from/to not provided, compute from/to
        $fromParam = $request->string('from')->toString();
        $toParam = $request->string('to')->toString();

        if ($request->filled('from') && $request->filled('to')) {
            $from = $this->parseDate($fromParam)?->startOfDay() ?? Carbon::now()->subDays(30)->startOfDay();
            $to = $this->parseDate($toParam)?->endOfDay() ?? Carbon::now()->endOfDay();
        } else {
            // reuse report's range mapping
            [$from, $to] = $this->earningsRangeDates($range);
            $sd = $this->parseDate($selectedDate ?: '');
            if ($sd && empty($fromParam) && empty($toParam)) {
                // selected date overrides computed window for list
                $from = $sd->startOfDay();
                $to = $sd->endOfDay();
            }
        }
        $type = $request->string('type', 'all')->toString(); // vendor|ecard|user|all

        $rows = collect();

        // User registrations
        if (in_array($type, ['all', 'user'], true)) {
            $rows = $rows->merge(
                DB::table('registrations as r')
                    ->where('r.parent_id', $portalEcardId)
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

        // E-card registrations
        if (in_array($type, ['all', 'ecard'], true)) {
            $rows = $rows->merge(
                DB::table('ecard_registrations as e')
                    ->where('e.parent_id', $portalEcardId)
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

        // Vendors (no confirmed parent mapping; returning vendors created in range)
        if (in_array($type, ['all', 'vendor'], true)) {
            $rows = $rows->merge(
                DB::table('vendors as v')
                    ->whereBetween('v.created_at', [$from, $to])
                    ->select([
                        DB::raw("'vendor' as member_type"),
                        'v.id as entity_id',
                        DB::raw("COALESCE(v.business_name, CONCAT_WS(' ', v.first_name, v.last_name)) as name"),
                        DB::raw('v.vendor_number as red_id'),
                        'v.created_at as created_at',
                    ])->get()
            );
        }

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

