<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserUploadRewardReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.user-upload-reward.index');
    }

    public function data(Request $request)
    {
        $draw = (int) ($request->get('draw', 1));
        $start = (int) ($request->get('start', 0));
        $length = (int) ($request->get('length', 10));
        $search = trim((string) ($request->input('search.value') ?? ''));

        $orderTable = $this->detectOrderTable();

        $rewardExpr = $this->buildCoalesceExpr($orderTable, [
            'upload_reward_amount', 'upload_reward', 'reward_amount', 'reward', 'reward_amt', 'reward_cash',
        ]);

        $orderNoCol = $this->resolveExistingColumn($orderTable, ['order_no', 'order_number', 'orderid', 'order_id']);
        $orderDateCol = $this->resolveExistingColumn($orderTable, ['order_date', 'created_at', 'date', 'ordered_at']);
        $sellerIdCol = $this->resolveExistingColumn($orderTable, ['seller_id', 'vendor_id']);
        $buyerIdCol = $this->resolveExistingColumn($orderTable, ['buyer_id', 'purchase_id', 'user_id', 'customer_id']);

        $baseQuery = DB::table($orderTable.' as o')
            ->selectRaw(
                ($orderNoCol ? "o.$orderNoCol as order_no, " : '').
                ($orderDateCol ? "o.$orderDateCol as order_date, " : '').
                ($sellerIdCol ? "o.$sellerIdCol as seller_id, " : 'NULL as seller_id, ').
                ($buyerIdCol ? "o.$buyerIdCol as buyer_id, " : 'NULL as buyer_id, ').
                "($rewardExpr) as upload_reward_amount"
            );

        // Join seller
        if ($sellerIdCol) {
            $baseQuery->leftJoin('users as seller', DB::raw('seller.id'), '=', DB::raw("o.$sellerIdCol"));
        }
        // Join buyer
        if ($buyerIdCol) {
            $baseQuery->leftJoin('users as buyer', DB::raw('buyer.id'), '=', DB::raw("o.$buyerIdCol"));
        }

        // Filters
        $this->applyFilters($baseQuery, $orderDateCol, $orderNoCol, $search);

        // Records total (unfiltered)
        $recordsTotal = DB::table($orderTable)->count();

        // Count filtered
        $recordsFiltered = (clone $baseQuery)->count();

        // Summary
        $summaryRow = (clone $baseQuery)
            ->selectRaw("SUM($rewardExpr) as total_reward, AVG($rewardExpr) as avg_reward, SUM(CASE WHEN ($rewardExpr) > 0 THEN 1 ELSE 0 END) as count_reward")
            ->first();

        $totalReward = (float) ($summaryRow->total_reward ?? 0);
        $avgReward = (float) ($summaryRow->avg_reward ?? 0);
        $countReward = (int) ($summaryRow->count_reward ?? 0);

        // Pagination
        if ($length > 0) {
            $baseQuery->skip($start)->take($length);
        }

        $rows = $baseQuery
            ->orderBy($orderDateCol ?: 'o.id', 'desc')
            ->get()
            ->map(function ($row, $idx) {
                return [
                    'sr_no' => $idx + 1,
                    'order_no' => $row->order_no ?? '-',
                    'order_date' => $row->order_date ?? '-',
                    'seller' => $this->formatUser($row->seller_id ?? null, $row->seller_name ?? null),
                    'buyer' => $this->formatUser($row->buyer_id ?? null, $row->buyer_name ?? null),
                    'upload_reward_amount' => number_format((float) ($row->upload_reward_amount ?? 0), 2),
                ];
            });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
            'summary' => [
                'total_reward' => number_format($totalReward, 2),
                'average_reward' => number_format($avgReward, 2),
                'count_reward' => $countReward,
            ],
        ]);
    }

    private function detectOrderTable(): string
    {
        if (Schema::hasTable('orders')) {
            return 'orders';
        }
        if (Schema::hasTable('user_orders')) {
            return 'user_orders';
        }

        return 'orders';
    }

    private function buildCoalesceExpr(string $table, array $candidates): string
    {
        $existing = array_values(array_filter($candidates, fn ($c) => Schema::hasColumn($table, $c)));
        if (empty($existing)) {
            return '0';
        }
        $expr = '0';
        foreach (array_reverse($existing) as $col) {
            $expr = "COALESCE(o.$col, $expr)";
        }

        return $expr;
    }

    private function resolveExistingColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }

        return null;
    }

    private function applyFilters($query, ?string $orderDateCol, ?string $orderNoCol, string $search): void
    {
        $from = request('from_date');
        $to = request('to_date');
        if ($orderDateCol) {
            if ($from) {
                $query->whereDate("o.$orderDateCol", '>=', $from);
            }
            if ($to) {
                $query->whereDate("o.$orderDateCol", '<=', $to);
            }
        }

        if ($orderNoCol && ($orderNo = request('order_no'))) {
            $query->where("o.$orderNoCol", $orderNo);
        }

        if ($min = request('min_reward')) {
            $query->whereRaw('(COALESCE(upload_reward_amount,0)) >= ?', [(float) $min]);
        }
        if ($max = request('max_reward')) {
            $query->whereRaw('(COALESCE(upload_reward_amount,0)) <= ?', [(float) $max]);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search, $orderNoCol) {
                if ($orderNoCol) {
                    $q->orWhere("o.$orderNoCol", 'like', "%$search%");
                }
                $q->orWhere('seller.name', 'like', "%$search%")
                    ->orWhere('seller.email', 'like', "%$search%")
                    ->orWhere('buyer.name', 'like', "%$search%")
                    ->orWhere('buyer.email', 'like', "%$search%");
            });
        }
    }

    private function formatUser($id, $name): string
    {
        if (! $id && ! $name) {
            return '-';
        }
        if ($id && $name) {
            return $id.' ('.$name.')';
        }
        if ($id) {
            return (string) $id;
        }

        return (string) $name;
    }
}
