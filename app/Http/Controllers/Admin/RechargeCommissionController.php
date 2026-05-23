<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RechargeCommissionRule;
use App\Models\RechargeOperator;
use App\Models\RechargeService;
use Illuminate\Http\Request;

class RechargeCommissionController extends Controller
{
    private array $departmentLevels = [
        'state_level' => 'State e-Card Seva',
        'district_level' => 'District e-Card Seva',
        'block_level' => 'Block - e-Card Seva',
        'panchayat_level' => 'G P M e-Card Seva',
        'village_level' => 'e-Card Seva',
        'customer' => 'Member',
    ];

    public function index(Request $request)
    {
        $query = RechargeCommissionRule::query()
            ->with(['service', 'operator'])
            ->orderByDesc('id');

        if ($request->filled('service_id')) {
            $query->where('recharge_service_id', (int) $request->input('service_id'));
        }
        if ($request->filled('operator_id')) {
            $query->where('recharge_operator_id', (int) $request->input('operator_id'));
        }
        if ($request->filled('department_level')) {
            $query->where('department_level', $request->input('department_level'));
        }
        if ($request->filled('status')) {
            $status = $request->input('status') === 'active' ? 1 : 0;
            $query->where('is_active', $status);
        }

        $perPage = (int) ($request->get('per_page', 15));
        $rules = $query->paginate($perPage)->withQueryString();

        $services = RechargeService::orderBy('service_name')->get();
        $operators = RechargeOperator::orderBy('operator_name')->get();

        return view('admin.recharge-commissions.index', [
            'rules' => $rules,
            'services' => $services,
            'operators' => $operators,
            'departmentLevels' => $this->departmentLevels,
        ]);
    }

    public function create()
    {
        $services = RechargeService::orderBy('service_name')->get();
        $operators = RechargeOperator::orderBy('operator_name')->get();

        return view('admin.recharge-commissions.create', [
            'services' => $services,
            'operators' => $operators,
            'departmentLevels' => $this->departmentLevels,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['created_by_user_id'] = auth()->id();

        RechargeCommissionRule::create($data);

        return redirect()->route('admin.recharge-commissions.index')
            ->with('success', 'Commission rule created successfully.');
    }

    public function edit(int $id)
    {
        $rule = RechargeCommissionRule::find($id);
        if (! $rule) {
            return redirect()->route('admin.recharge-commissions.index')
                ->with('error', 'Commission rule not found.');
        }

        $services = RechargeService::orderBy('service_name')->get();
        $operators = RechargeOperator::orderBy('operator_name')->get();

        return view('admin.recharge-commissions.edit', [
            'rule' => $rule,
            'services' => $services,
            'operators' => $operators,
            'departmentLevels' => $this->departmentLevels,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $rule = RechargeCommissionRule::find($id);
        if (! $rule) {
            return redirect()->route('admin.recharge-commissions.index')
                ->with('error', 'Commission rule not found.');
        }

        $data = $this->validated($request);
        $rule->update($data);

        return redirect()->route('admin.recharge-commissions.index')
            ->with('success', 'Commission rule updated successfully.');
    }

    public function destroy(int $id)
    {
        $rule = RechargeCommissionRule::find($id);
        if (! $rule) {
            return redirect()->route('admin.recharge-commissions.index')
                ->with('error', 'Commission rule not found.');
        }

        $rule->delete();

        return redirect()->route('admin.recharge-commissions.index')
            ->with('success', 'Commission rule deleted successfully.');
    }

    public function toggleStatus(int $id)
    {
        $rule = RechargeCommissionRule::find($id);
        if (! $rule) {
            return redirect()->route('admin.recharge-commissions.index')
                ->with('error', 'Commission rule not found.');
        }

        $rule->is_active = ! $rule->is_active;
        $rule->save();

        return redirect()->route('admin.recharge-commissions.index')
            ->with('success', 'Commission rule status updated successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'recharge_service_id' => ['required', 'integer', 'exists:recharge_services,id'],
            'recharge_operator_id' => ['nullable', 'integer', 'exists:recharge_operators,id'],
            'department_level' => ['nullable', 'string', 'in:'.implode(',', array_keys($this->departmentLevels))],
            'commission_type' => ['required', 'in:percentage,flat'],
            'commission_value' => ['required', 'numeric', 'min:0'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (($data['commission_type'] ?? null) === 'percentage' && (float) ($data['commission_value'] ?? 0) > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'commission_value' => ['Percentage commission cannot exceed 100.'],
            ]);
        }

        if (
            isset($data['min_amount'], $data['max_amount']) &&
            $data['min_amount'] !== null &&
            $data['max_amount'] !== null &&
            (float) $data['min_amount'] > (float) $data['max_amount']
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'max_amount' => ['Max amount must be greater than or equal to Min amount.'],
            ]);
        }

        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        return $data;
    }
}
