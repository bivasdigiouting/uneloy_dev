<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RechargeOperatorStoreRequest;
use App\Http\Requests\Admin\RechargeOperatorUpdateRequest;
use App\Repositories\Interfaces\RechargeOperatorRepositoryInterface;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RechargeOperatorController extends Controller
{
    protected RechargeOperatorRepositoryInterface $operators;

    protected RechargeServiceRepositoryInterface $services;

    public function __construct(
        RechargeOperatorRepositoryInterface $operators,
        RechargeServiceRepositoryInterface $services
    ) {
        $this->operators = $operators;
        $this->services = $services;
    }

    // List operators
    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page', 15));
        $operators = $this->operators->paginate($perPage);

        return view('admin.recharge-operators.index', compact('operators'));
    }

    // Show create form
    public function create()
    {
        $services = $this->services->getActive();

        return view('admin.recharge-operators.create', compact('services'));
    }

    // Store new operator
    public function store(RechargeOperatorStoreRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        if ($request->hasFile('operator_logo')) {
            $data['operator_logo'] = $request->file('operator_logo')->store('operator_logos', 'public');
        }

        $this->operators->create($data);

        return redirect()->route('admin.recharge-operators.index')
            ->with('success', 'Recharge operator created successfully.');
    }

    // Show edit form
    public function edit(int $id)
    {
        $operator = $this->operators->find($id);
        if (! $operator) {
            return redirect()->route('admin.recharge-operators.index')
                ->with('error', 'Recharge operator not found.');
        }
        $services = $this->services->getActive();

        return view('admin.recharge-operators.edit', compact('operator', 'services'));
    }

    // Update operator
    public function update(RechargeOperatorUpdateRequest $request, int $id)
    {
        $data = $request->validated();
        if (isset($data['is_active'])) {
            $data['is_active'] = (bool) $data['is_active'];
        }

        $operator = $this->operators->find($id);

        if ($request->hasFile('operator_logo')) {
            if ($operator && $operator->operator_logo) {
                Storage::disk('public')->delete($operator->operator_logo);
            }
            $data['operator_logo'] = $request->file('operator_logo')->store('operator_logos', 'public');
        }

        $updated = $this->operators->update($id, $data);
        if (! $updated) {
            return redirect()->route('admin.recharge-operators.index')
                ->with('error', 'Unable to update operator.');
        }

        return redirect()->route('admin.recharge-operators.index')
            ->with('success', 'Recharge operator updated successfully.');
    }

    // Delete operator
    public function destroy(int $id)
    {
        $deleted = $this->operators->delete($id);

        return redirect()->route('admin.recharge-operators.index')
            ->with($deleted ? 'success' : 'error', $deleted ? 'Recharge operator deleted successfully.' : 'Unable to delete operator.');
    }

    // Toggle status
    public function toggleStatus(int $id)
    {
        $toggled = $this->operators->toggleStatus($id);
        if (request()->wantsJson()) {
            return response()->json(['success' => $toggled]);
        }

        return redirect()->route('admin.recharge-operators.index')
            ->with($toggled ? 'success' : 'error', $toggled ? 'Status updated.' : 'Unable to update status.');
    }
}
