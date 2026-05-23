<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FirstRechargePlanStoreRequest;
use App\Http\Requests\Admin\FirstRechargePlanUpdateRequest;
use App\Models\Department;
use App\Models\FirstRechargePlan;
use App\Models\FirstRechargePlanCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FirstRechargePlanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page', 15));

        $plans = FirstRechargePlan::query()
            ->orderByDesc('id')
            ->paginate($perPage);

        return view('admin.first-recharge-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.first-recharge-plans.create');
    }

    public function store(FirstRechargePlanStoreRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);
        $data['total_value'] = $this->calculateTotalValue($data['plan_value'], $data['bonus_value']);

        FirstRechargePlan::create($data);

        return redirect()->route('admin.first-recharge-plans.index')
            ->with('success', 'First recharge plan created successfully.');
    }

    public function edit(FirstRechargePlan $firstRechargePlan)
    {
        return view('admin.first-recharge-plans.edit', [
            'plan' => $firstRechargePlan,
        ]);
    }

    public function update(FirstRechargePlanUpdateRequest $request, FirstRechargePlan $firstRechargePlan)
    {
        $data = $request->validated();
        if (isset($data['is_active'])) {
            $data['is_active'] = (bool) $data['is_active'];
        }
        $data['total_value'] = $this->calculateTotalValue($data['plan_value'], $data['bonus_value']);

        $firstRechargePlan->update($data);

        return redirect()->route('admin.first-recharge-plans.index')
            ->with('success', 'First recharge plan updated successfully.');
    }

    public function destroy(FirstRechargePlan $firstRechargePlan)
    {
        $deleted = $firstRechargePlan->delete();

        return redirect()->route('admin.first-recharge-plans.index')
            ->with($deleted ? 'success' : 'error', $deleted ? 'First recharge plan deleted successfully.' : 'Unable to delete first recharge plan.');
    }

    public function toggleStatus(int $id)
    {
        $plan = FirstRechargePlan::find($id);
        if (! $plan) {
            return redirect()->route('admin.first-recharge-plans.index')
                ->with('error', 'First recharge plan not found.');
        }

        $plan->is_active = ! $plan->is_active;
        $plan->save();

        return redirect()->route('admin.first-recharge-plans.index')
            ->with('success', 'Status updated.');
    }

    public function commissions(FirstRechargePlan $firstRechargePlan)
    {
        $levelDepartmentNames = [
            'State e-Card Seva',
            'District e-Card Seva',
            'Block - e-Card Seva',
            'G P M e-Card Seva',
            'e-Card Seva',
        ];

        $departments = collect($levelDepartmentNames)
            ->map(function (string $name) {
                return Department::firstOrCreate(
                    ['department_name' => $name],
                    ['is_active' => true]
                );
            })
            ->values();

        $existing = FirstRechargePlanCommission::query()
            ->where('first_recharge_plan_id', $firstRechargePlan->id)
            ->whereIn('department_id', $departments->pluck('id'))
            ->get()
            ->keyBy('department_id');

        $rows = $departments->map(function (Department $department) use ($existing) {
            $commission = $existing->get($department->id);

            return [
                'department_id' => $department->id,
                'department_name' => $department->department_name,
                'commission_amount' => $commission ? (float) $commission->commission_amount : 0.0,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'plan_id' => $firstRechargePlan->id,
            'rows' => $rows,
        ]);
    }

    public function updateCommissions(Request $request, FirstRechargePlan $firstRechargePlan)
    {
        $validator = Validator::make($request->all(), [
            'commissions' => ['required', 'array'],
            'commissions.*.department_id' => ['required', 'integer', 'exists:departments,id'],
            'commissions.*.commission_amount' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $items = (array) $request->input('commissions', []);
        $departmentIds = collect($items)->pluck('department_id')->unique()->values();

        if ($departmentIds->count() > 5) {
            return response()->json([
                'success' => false,
                'message' => 'Too many departments provided.',
            ], 422);
        }

        foreach ($items as $item) {
            FirstRechargePlanCommission::updateOrCreate(
                [
                    'first_recharge_plan_id' => $firstRechargePlan->id,
                    'department_id' => (int) $item['department_id'],
                ],
                [
                    'commission_amount' => (float) $item['commission_amount'],
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Commission updated successfully.',
        ]);
    }

    private function calculateTotalValue($planValue, $bonusValue): float
    {
        return round(((float) $planValue) + ((float) $bonusValue), 2);
    }
}
