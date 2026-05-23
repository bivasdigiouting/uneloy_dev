<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpsLevelDistribution;
use App\Models\EpsLevelUserDistribution;
use App\Models\LevelWiseProductCommission;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EpsLevelFundController extends Controller
{
    /** Display the Global Disburs. Level Fund page */
    public function index()
    {
        return view('admin.eps-level-fund.index');
    }

    /** Data endpoint for listing users and their distributed fund for a given distribution */
    public function data(Request $request)
    {
        $distributionId = $request->query('distribution_id');

        $query = EpsLevelUserDistribution::query()
            ->with('registration')
            ->when($distributionId, function ($q) use ($distributionId) {
                $q->where('distribution_id', $distributionId);
            }, function ($q) {
                // Default: latest distribution
                $latest = EpsLevelDistribution::orderByDesc('created_at')->first();
                if ($latest) {
                    $q->where('distribution_id', $latest->id);
                } else {
                    $q->whereRaw('1 = 0'); // No data
                }
            })
            ->orderBy('level_type');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('level', function ($row) {
                return $this->formatLevelType($row->level_type);
            })
            ->addColumn('user_id', function ($row) {
                return $row->registration ? ($row->registration->user_id ?? '-') : '-';
            })
            ->addColumn('user_name', function ($row) {
                if (! $row->registration) {
                    return '-';
                }
                $first = $row->registration->first_name ?? '';
                $last = $row->registration->last_name ?? '';
                $name = trim($first.' '.$last);

                return $name ?: '-';
            })
            ->addColumn('mobile_no', function ($row) {
                return $row->registration ? ($row->registration->mobile_no ?? '-') : '-';
            })
            ->addColumn('fund', function ($row) {
                return number_format($row->amount, 2);
            })
            ->make(true);
    }

    /** Handle distribution of fund across levels based on commission master */
    public function distribute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $totalAmount = (float) $request->amount;

        // Use first active LevelWiseProductCommission as commission master
        $commission = LevelWiseProductCommission::active()->first();
        if (! $commission) {
            return response()->json([
                'success' => false,
                'message' => 'No active commission master found. Please configure Level Wise Product Commission.',
            ], 400);
        }

        $percentages = [
            'state_level' => (float) $commission->state_member_commission,
            'district_level' => (float) $commission->district_member_commission,
            'block_level' => (float) $commission->block_member_commission,
            'panchayat_level' => (float) $commission->panchayat_member_commission,
            'village_level' => (float) $commission->village_member_commission,
        ];

        // Calculate share amounts by level
        $levelAllocations = [];
        foreach ($percentages as $level => $pct) {
            $levelAllocations[$level] = round(($totalAmount * $pct) / 100, 2);
        }

        // Fetch registrations by level (approved only)
        $registrationsByLevel = [
            'state_level' => Registration::where('department_level', 'state_level')->where('status', 'approved')->select('id')->get(),
            'district_level' => Registration::where('department_level', 'district_level')->where('status', 'approved')->select('id')->get(),
            'block_level' => Registration::where('department_level', 'block_level')->where('status', 'approved')->select('id')->get(),
            'panchayat_level' => Registration::where('department_level', 'panchayat_level')->where('status', 'approved')->select('id')->get(),
            'village_level' => Registration::where('department_level', 'village_level')->where('status', 'approved')->select('id')->get(),
        ];

        // Compute per-user allocation per level
        $perUserAlloc = [];
        foreach ($registrationsByLevel as $level => $regs) {
            $count = $regs->count();
            $alloc = $levelAllocations[$level];
            $perUserAlloc[$level] = $count > 0 ? round($alloc / $count, 2) : 0.00;
        }

        // Persist distribution and user allocations
        try {
            DB::beginTransaction();

            $distribution = EpsLevelDistribution::create([
                'total_amount' => $totalAmount,
                'commission_source_type' => 'level_wise_product_commission',
                'commission_source_id' => $commission->id,
                'commission_breakdown' => [
                    'percentages' => $percentages,
                    'level_allocations' => $levelAllocations,
                    'per_user_allocations' => $perUserAlloc,
                ],
                'created_by_user_id' => Auth::id() ?? 1,
            ]);

            // Insert per-user records
            foreach ($registrationsByLevel as $level => $regs) {
                $amountPerUser = $perUserAlloc[$level];
                if ($amountPerUser <= 0) {
                    continue;
                }
                foreach ($regs as $reg) {
                    EpsLevelUserDistribution::create([
                        'distribution_id' => $distribution->id,
                        'level_type' => $level,
                        'registration_id' => $reg->id,
                        'amount' => $amountPerUser,
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Distribution failed. '.$e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fund distributed successfully.',
            'distribution_id' => $distribution->id,
            'summary' => [
                'total_amount' => $totalAmount,
                'percentages' => $percentages,
                'level_allocations' => $levelAllocations,
                'per_user_allocations' => $perUserAlloc,
            ],
        ]);
    }

    private function formatLevelType(string $level): string
    {
        return match ($level) {
            'state_level' => 'State e-Card Seva',
            'district_level' => 'District e-Card Seva',
            'city_level' => 'City Level',
            'block_level' => 'Block - e-Card Seva',
            'panchayat_level' => 'G P M e-Card Seva',
            'village_level' => 'e-Card Seva',
            default => ucfirst(str_replace('_', ' ', $level)),
        };
    }
}
