<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RechargeServiceStoreRequest;
use App\Http\Requests\Admin\RechargeServiceUpdateRequest;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use Illuminate\Http\Request;

class RechargeServiceController extends Controller
{
    protected RechargeServiceRepositoryInterface $services;

    public function __construct(RechargeServiceRepositoryInterface $services)
    {
        $this->services = $services;
    }

    // List services
    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page', 15));
        $services = $this->services->paginate($perPage);

        return view('admin.recharge-services.index', compact('services'));
    }

    // Show create form
    public function create()
    {
        return view('admin.recharge-services.create');
    }

    // Store new service
    public function store(RechargeServiceStoreRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $this->services->create($data);

        return redirect()->route('admin.recharge-services.index')
            ->with('success', 'Recharge service created successfully.');
    }

    // Show edit form
    public function edit(int $id)
    {
        $service = $this->services->find($id);
        if (! $service) {
            return redirect()->route('admin.recharge-services.index')
                ->with('error', 'Recharge service not found.');
        }

        return view('admin.recharge-services.edit', compact('service'));
    }

    // Update service
    public function update(RechargeServiceUpdateRequest $request, int $id)
    {
        $data = $request->validated();
        if (isset($data['is_active'])) {
            $data['is_active'] = (bool) $data['is_active'];
        }

        $updated = $this->services->update($id, $data);
        if (! $updated) {
            return redirect()->route('admin.recharge-services.index')
                ->with('error', 'Unable to update service.');
        }

        return redirect()->route('admin.recharge-services.index')
            ->with('success', 'Recharge service updated successfully.');
    }

    // Delete service
    public function destroy(int $id)
    {
        $deleted = $this->services->delete($id);

        return redirect()->route('admin.recharge-services.index')
            ->with($deleted ? 'success' : 'error', $deleted ? 'Recharge service deleted successfully.' : 'Unable to delete service.');
    }

    // Toggle status
    public function toggleStatus(int $id)
    {
        $toggled = $this->services->toggleStatus($id);
        if (request()->wantsJson()) {
            return response()->json(['success' => $toggled]);
        }

        return redirect()->route('admin.recharge-services.index')
            ->with($toggled ? 'success' : 'error', $toggled ? 'Status updated.' : 'Unable to update status.');
    }
}
