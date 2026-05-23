<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyUpiRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CompanyUpiController extends Controller
{
    protected CompanyUpiRepositoryInterface $companyUpiRepository;

    public function __construct(CompanyUpiRepositoryInterface $companyUpiRepository)
    {
        $this->companyUpiRepository = $companyUpiRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companyUpis = $this->companyUpiRepository->getForDataTables();

            return DataTables::of($companyUpis)
                ->addIndexColumn()
                ->addColumn('qr_code_display', function ($companyUpi) {
                    if ($companyUpi->qr_code) {
                        return '<img src="'.asset('storage/'.$companyUpi->qr_code).'" alt="QR Code" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">';
                    }

                    return '<span class="text-muted">No QR Code</span>';
                })
                ->addColumn('status', function ($companyUpi) {
                    return $companyUpi->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($companyUpi) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.company-upis.edit', $companyUpi->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteCompanyUpi('.$companyUpi->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($companyUpi) {
                    return $companyUpi->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['qr_code_display', 'status', 'action'])
                ->make(true);
        }

        return view('admin.company-upis.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.company-upis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'upi_id' => 'required|string|max:255|unique:company_upis,upi_id',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ]);

        try {
            $data = $request->only(['upi_id', 'status', 'remarks']);

            // Handle QR code upload
            if ($request->hasFile('qr_code')) {
                $qrCodePath = $request->file('qr_code')->store('company-upi-qr-codes', 'public');
                $data['qr_code'] = $qrCodePath;
            }

            $this->companyUpiRepository->createCompanyUpi($data);

            return redirect()->route('admin.company-upis.index')
                ->with('success', 'Company UPI created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create company UPI. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $companyUpi = $this->companyUpiRepository->findCompanyUpi($id);

        if (! $companyUpi) {
            abort(404, 'Company UPI not found');
        }

        return view('admin.company-upis.edit', compact('companyUpi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'upi_id' => 'required|string|max:255|unique:company_upis,upi_id,'.$id,
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ]);

        try {
            $companyUpi = $this->companyUpiRepository->findCompanyUpi($id);

            if (! $companyUpi) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Company UPI not found.');
            }

            $data = $request->only(['upi_id', 'status', 'remarks']);

            // Handle QR code upload
            if ($request->hasFile('qr_code')) {
                // Delete old QR code if exists
                if ($companyUpi->qr_code && Storage::disk('public')->exists($companyUpi->qr_code)) {
                    Storage::disk('public')->delete($companyUpi->qr_code);
                }

                $qrCodePath = $request->file('qr_code')->store('company-upi-qr-codes', 'public');
                $data['qr_code'] = $qrCodePath;
            }

            $updated = $this->companyUpiRepository->updateCompanyUpi($id, $data);

            if (! $updated) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Company UPI not found.');
            }

            return redirect()->route('admin.company-upis.index')
                ->with('success', 'Company UPI updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update company UPI. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $companyUpi = $this->companyUpiRepository->findCompanyUpi($id);

            if (! $companyUpi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company UPI not found.',
                ], 404);
            }

            // Delete QR code file if exists
            if ($companyUpi->qr_code && Storage::disk('public')->exists($companyUpi->qr_code)) {
                Storage::disk('public')->delete($companyUpi->qr_code);
            }

            $deleted = $this->companyUpiRepository->deleteCompanyUpi($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company UPI not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Company UPI deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete company UPI. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle company UPI status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $updated = $this->companyUpiRepository->toggleStatus($id);

            if (! $updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company UPI not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Company UPI status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update company UPI status. Please try again.',
            ], 500);
        }
    }
}
