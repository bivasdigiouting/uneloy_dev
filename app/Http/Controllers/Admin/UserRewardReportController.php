<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserRewardReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.user-reward.index');
    }

    public function data(Request $request)
    {
        [$orderTable, $orderDateCol] = $this->detectOrderTable();

        // Candidate reward columns across possible schemas
        $rewardCandidates = [
            'reward_amount', 'reward', 'reward_points', 'points', 'points_earned', 'earned_reward', 'user_reward', 'user_reward_amount', 'reward_amt', 'reward_cash',
        ];
        $rewardExpr = $this->buildCoalesceExpr($orderTable, $rewardCandidates, 0, 'reward_amount');

        $query = DB::table($orderTable.' as o')
            ->leftJoin('users as seller', 'o.seller_id', '=', 'seller.id')
            ->leftJoin('users as buyer', 'o.user_id', '=', 'buyer.id')
            ->selectRaw(
                implode(', ', [
                    'o.id',
                    'o.order_no',
                    "o.{$orderDateCol} as order_date",
                    'o.seller_id',
                    'seller.name as seller_name',
                    'o.user_id as buyer_id',
                    'buyer.name as buyer_name',
                    "({$rewardExpr}) as reward_amount",
                ])
            );

        $this->applyFilters($query, $request, $orderDateCol, $rewardExpr);

        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $page = (int) floor($start / max($length, 1)) + 1;

        $totalRecords = (clone $query)->count();

        $rows = $query
            ->orderByDesc('o.id')
            ->forPage($page, $length)
            ->get();

        $summary = $this->computeSummary((clone $query), $rewardExpr);

        $data = [];
        $sr = $start + 1;
        foreach ($rows as $r) {
            $data[] = [
                'sr_no' => $sr++,
                'order_no' => $r->order_no,
                'order_date' => $r->order_date ? Carbon::parse($r->order_date)->format('Y-m-d') : null,
                'seller' => $this->formatUser($r->seller_id, $r->seller_name),
                'buyer' => $this->formatUser($r->buyer_id, $r->buyer_name),
                'reward_amount' => number_format((float) ($r->reward_amount ?? 0), 2),
            ];
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
            'summary' => $summary,
        ]);
    }

    private function detectOrderTable(): array
    {
        $table = Schema::hasTable('orders') ? 'orders' : (Schema::hasTable('user_orders') ? 'user_orders' : 'orders');
        $dateCol = $this->resolveExistingColumn($table, ['order_date', 'created_at', 'transaction_date', 'date'], 'created_at');

        return [$table, $dateCol];
    }

    private function buildCoalesceExpr(string $table, array $candidates, $default = 0, string $alias = 'amount'): string
    {
        $available = [];
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                $available[] = "o.$c";
            }
        }
        if (empty($available)) {
            return is_numeric($default) ? (string) $default : DB::getPdo()->quote((string) $default);
        }

        return 'COALESCE('.implode(', ', $available).', '.(is_numeric($default) ? (string) $default : DB::getPdo()->quote((string) $default)).')';
    }

    private function resolveExistingColumn(string $table, array $candidates, string $fallback): string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }

        return $fallback;
    }

    private function applyFilters($query, Request $request, string $orderDateCol, string $rewardExpr): void
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        if ($from) {
            $query->whereDate("o.$orderDateCol", '>=', $from);
        }
        if ($to) {
            $query->whereDate("o.$orderDateCol", '<=', $to);
        }

        $orderNo = $request->input('order_no');
        if ($orderNo) {
            $query->where('o.order_no', $orderNo);
        }

        $min = $request->input('min_reward');
        if ($min !== null && $min !== '') {
            $query->whereRaw("({$rewardExpr}) >= ?", [(float) $min]);
        }

        $max = $request->input('max_reward');
        if ($max !== null && $max !== '') {
            $query->whereRaw("({$rewardExpr}) <= ?", [(float) $max]);
        }

        $search = trim((string) $request->input('search')); // free-text search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('o.order_no', 'like', "%$search%")
                    ->orWhere('seller.name', 'like', "%$search%")
                    ->orWhere('seller.email', 'like', "%$search%")
                    ->orWhere('buyer.name', 'like', "%$search%")
                    ->orWhere('buyer.email', 'like', "%$search%");
            });
        }
    }

    private function computeSummary($query, string $rewardExpr): array
    {
        $totals = (clone $query)
            ->selectRaw("SUM(({$rewardExpr})) as total, AVG(({$rewardExpr})) as avg, COUNT(*) as cnt")
            ->first();

        return [
            'total' => number_format((float) ($totals->total ?? 0), 2),
            'average' => number_format((float) ($totals->avg ?? 0), 2),
            'count' => (int) ($totals->cnt ?? 0),
        ];
    }

    private function formatUser($id, $name): string
    {
        if (! $id && ! $name) {
            return '-';
        }
        if ($id && $name) {
            return $id.' ('.$name.')';
        }

        return (string) ($id ?? $name ?? '-');
    }
}
