<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\BankRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    protected BankRepositoryInterface $bankRepository;

    public function __construct(BankRepositoryInterface $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banks = $this->bankRepository->getForDataTables();

            return DataTables::of($banks)
                ->addIndexColumn()
                ->addColumn('status', function ($bank) {
                    return $bank->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($bank) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.banks.edit', $bank->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteBank('.$bank->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($bank) {
                    return $bank->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.banks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'bank_name' => 'required|string|max:255|unique:banks,bank_name',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $this->bankRepository->createBank($request->only(['bank_name', 'status']));

            return redirect()->route('admin.banks.index')
                ->with('success', 'Bank created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create bank. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $bank = $this->bankRepository->findBank($id);

        if (! $bank) {
            abort(404, 'Bank not found');
        }

        return view('admin.banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'bank_name' => 'required|string|max:255|unique:banks,bank_name,'.$id,
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $updated = $this->bankRepository->updateBank($id, $request->only(['bank_name', 'status']));

            if (! $updated) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bank not found.');
            }

            return redirect()->route('admin.banks.index')
                ->with('success', 'Bank updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update bank. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->bankRepository->deleteBank($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bank not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bank deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bank. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle bank status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $updated = $this->bankRepository->toggleStatus($id);

            if (! $updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bank not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bank status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank status. Please try again.',
            ], 500);
        }
    }
}
