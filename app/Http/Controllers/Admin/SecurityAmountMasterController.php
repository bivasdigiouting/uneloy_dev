<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAmountMaster;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SecurityAmountMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $securityAmounts = SecurityAmountMaster::query();

            return DataTables::of($securityAmounts)
                ->addIndexColumn()
                ->addColumn('status', function ($securityAmount) {
                    return $securityAmount->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($securityAmount) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.security-amount-master.show', $securityAmount->id).'" class="btn btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.security-amount-master.edit', $securityAmount->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteSecurityAmount('.$securityAmount->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->editColumn('created_at', function ($securityAmount) {
                    return $securityAmount->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.security-amount-master.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.security-amount-master.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'state_level_amount' => 'required|numeric|min:0',
            'district_level_amount' => 'required|numeric|min:0',
            'block_level_amount' => 'required|numeric|min:0',
            'panchayat_level_amount' => 'required|numeric|min:0',
            'village_level_amount' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            SecurityAmountMaster::create([
                'state_level_amount' => $request->state_level_amount,
                'district_level_amount' => $request->district_level_amount,
                'block_level_amount' => $request->block_level_amount,
                'panchayat_level_amount' => $request->panchayat_level_amount,
                'village_level_amount' => $request->village_level_amount,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->route('admin.security-amount-master.index')
                ->with('success', 'Security amount master created successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create security amount master: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SecurityAmountMaster $securityAmountMaster): View
    {
        return view('admin.security-amount-master.show', compact('securityAmountMaster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecurityAmountMaster $securityAmountMaster): View
    {
        return view('admin.security-amount-master.edit', compact('securityAmountMaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SecurityAmountMaster $securityAmountMaster): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'state_level_amount' => 'required|numeric|min:0',
            'district_level_amount' => 'required|numeric|min:0',
            'block_level_amount' => 'required|numeric|min:0',
            'panchayat_level_amount' => 'required|numeric|min:0',
            'village_level_amount' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $securityAmountMaster->update([
                'state_level_amount' => $request->state_level_amount,
                'district_level_amount' => $request->district_level_amount,
                'block_level_amount' => $request->block_level_amount,
                'panchayat_level_amount' => $request->panchayat_level_amount,
                'village_level_amount' => $request->village_level_amount,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->route('admin.security-amount-master.index')
                ->with('success', 'Security amount master updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update security amount master: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecurityAmountMaster $securityAmountMaster): JsonResponse
    {
        try {
            $securityAmountMaster->delete();

            return response()->json([
                'success' => true,
                'message' => 'Security amount master deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete security amount master: '.$e->getMessage(),
            ], 500);
        }
    }
}
