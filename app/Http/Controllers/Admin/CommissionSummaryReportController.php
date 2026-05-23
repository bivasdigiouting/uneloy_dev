<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelWiseProductCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.reports.commission-summary.index');
    }

    public function data(Request $request)
    {
        $query = LevelWiseProductCommission::query()
            ->with('productCategory')
            ->select([
                'id',
                'product_category_id',
                'state_member_commission',
                'district_member_commission',
                'block_member_commission',
                'panchayat_member_commission',
                'village_member_commission',
                'customer_commission',
                'is_active',
            ]);

        // Filters
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('category')) {
            $category = trim($request->get('category'));
            $query->whereHas('productCategory', function ($q) use ($category) {
                $q->where('name', 'like', "%$category%");
            });
        }

        if ($request->filled('min_commission')) {
            $query->where('customer_commission', '>=', (float) $request->get('min_commission'));
        }
        if ($request->filled('max_commission')) {
            $query->where('customer_commission', '<=', (float) $request->get('max_commission'));
        }

        // Pagination and ordering for DataTables
        $columns = [
            'product_category', // virtual
            'state_member_commission',
            'district_member_commission',
            'block_member_commission',
            'panchayat_member_commission',
            'village_member_commission',
            'customer_commission',
            'is_active',
        ];

        $draw = (int) ($request->get('draw') ?? 1);
        $start = (int) ($request->get('start') ?? 0);
        $length = (int) ($request->get('length') ?? 10);

        $recordsTotal = (clone $query)->count();

        // Ordering
        if ($request->has('order')) {
            foreach ($request->get('order') as $order) {
                $colIndex = (int) $order['column'];
                $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
                $colName = $columns[$colIndex] ?? null;
                if ($colName && $colName !== 'product_category') {
                    $query->orderBy($colName, $dir);
                } else {
                    // Order by category name when product_category is selected
                    $query->join('product_categories as pc', 'pc.id', '=', 'level_wise_product_commissions.product_category_id')
                        ->orderBy('pc.name', $dir)
                        ->select('level_wise_product_commissions.*');
                }
            }
        }

        $dataRows = $query->skip($start)->take($length)->get();

        $data = $dataRows->map(function ($row) {
            return [
                'product_category' => optional($row->productCategory)->name ?? 'N/A',
                'state_member_commission' => number_format((float) $row->state_member_commission, 2).'%',
                'district_member_commission' => number_format((float) $row->district_member_commission, 2).'%',
                'block_member_commission' => number_format((float) $row->block_member_commission, 2).'%',
                'panchayat_member_commission' => number_format((float) $row->panchayat_member_commission, 2).'%',
                'village_member_commission' => number_format((float) $row->village_member_commission, 2).'%',
                'customer_commission' => number_format((float) $row->customer_commission, 2).'%',
                'is_active' => $row->is_active ? 'Active' : 'Inactive',
            ];
        })->toArray();

        // Summary
        $base = (clone $query)->select([
            DB::raw('AVG(state_member_commission) as avg_state'),
            DB::raw('AVG(district_member_commission) as avg_district'),
            DB::raw('AVG(block_member_commission) as avg_block'),
            DB::raw('AVG(panchayat_member_commission) as avg_panchayat'),
            DB::raw('AVG(village_member_commission) as avg_village'),
            DB::raw('AVG(customer_commission) as avg_customer'),
            DB::raw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count'),
            DB::raw('SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_count'),
        ])->first();

        $summary = [
            'avg_state' => number_format((float) ($base->avg_state ?? 0), 2).'%',
            'avg_district' => number_format((float) ($base->avg_district ?? 0), 2).'%',
            'avg_block' => number_format((float) ($base->avg_block ?? 0), 2).'%',
            'avg_panchayat' => number_format((float) ($base->avg_panchayat ?? 0), 2).'%',
            'avg_village' => number_format((float) ($base->avg_village ?? 0), 2).'%',
            'avg_customer' => number_format((float) ($base->avg_customer ?? 0), 2).'%',
            'active_count' => (int) ($base->active_count ?? 0),
            'inactive_count' => (int) ($base->inactive_count ?? 0),
            'total_categories' => (int) $recordsTotal,
        ];

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal, // filters already applied to $query
            'data' => $data,
            'summary' => $summary,
        ]);
    }
}
