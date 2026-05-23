<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\VendorTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorTypeController extends Controller
{
    protected $vendorTypeRepository;

    public function __construct(VendorTypeRepositoryInterface $vendorTypeRepository)
    {
        $this->vendorTypeRepository = $vendorTypeRepository;
    }

    /**
     * Display a listing of the vendor types.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->vendorTypeRepository->getForDataTables();
        }

        $totalVendorTypes = $this->vendorTypeRepository->getCountByStatus();
        $activeVendorTypes = $this->vendorTypeRepository->getCountByStatus(true);
        $inactiveVendorTypes = $this->vendorTypeRepository->getCountByStatus(false);

        return view('admin.vendor-types.index', compact('totalVendorTypes', 'activeVendorTypes', 'inactiveVendorTypes'));
    }

    /**
     * Show the form for creating a new vendor type.
     */
    public function create()
    {
        return view('admin.vendor-types.create');
    }

    /**
     * Store a newly created vendor type in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_type' => 'required|string|max:255|unique:vendor_types,vendor_type',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->vendorTypeRepository->create([
                'vendor_type' => $request->vendor_type,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.vendor-types.index')
                ->with('success', 'Vendor type created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create vendor type. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified vendor type.
     */
    public function show($id)
    {
        $vendorType = $this->vendorTypeRepository->findById($id);

        return view('admin.vendor-types.show', compact('vendorType'));
    }

    /**
     * Show the form for editing the specified vendor type.
     */
    public function edit($id)
    {
        $vendorType = $this->vendorTypeRepository->findById($id);

        return view('admin.vendor-types.edit', compact('vendorType'));
    }

    /**
     * Update the specified vendor type in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vendor_type' => 'required|string|max:255|unique:vendor_types,vendor_type,'.$id,
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->vendorTypeRepository->update($id, [
                'vendor_type' => $request->vendor_type,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.vendor-types.index')
                ->with('success', 'Vendor type updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update vendor type. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified vendor type from storage.
     */
    public function destroy($id)
    {
        try {
            $vendorType = $this->vendorTypeRepository->findById($id);

            // Check if vendor type is being used by any vendors
            if ($vendorType->vendors()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete vendor type as it is being used by vendors.',
                ]);
            }

            $this->vendorTypeRepository->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Vendor type deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vendor type. Please try again.',
            ]);
        }
    }

    /**
     * Toggle the status of the specified vendor type.
     */
    public function toggleStatus($id)
    {
        try {
            $vendorType = $this->vendorTypeRepository->toggleStatus($id);

            return response()->json([
                'success' => true,
                'message' => 'Vendor type status updated successfully.',
                'status' => $vendorType->is_active ? 'Active' : 'Inactive',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor type status. Please try again.',
            ]);
        }
    }

    /**
     * Get active vendor types for dropdown
     */
    public function getActiveVendorTypes()
    {
        try {
            $vendorTypes = $this->vendorTypeRepository->getActive();

            return response()->json([
                'success' => true,
                'data' => $vendorTypes->map(function ($vendorType) {
                    return [
                        'id' => $vendorType->id,
                        'vendor_type' => $vendorType->vendor_type,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor types.',
            ]);
        }
    }
}
