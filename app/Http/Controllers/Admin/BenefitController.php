<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BenefitRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BenefitController extends Controller
{
    protected BenefitRepositoryInterface $benefitRepository;

    public function __construct(BenefitRepositoryInterface $benefitRepository)
    {
        $this->benefitRepository = $benefitRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->benefitRepository->getForDataTable();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('icon_preview', function ($benefit) {
                    $url = $benefit->icon_url ?? null;
                    if ($url) {
                        return '<img src="'.e($url).'" alt="icon" class="img-thumbnail" style="width:40px;height:40px;object-fit:cover;" />';
                    }

                    return '<span class="badge bg-secondary">No Icon</span>';
                })
                ->addColumn('status_badge', function ($benefit) {
                    return $benefit->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_at', function ($benefit) {
                    return $benefit->created_at->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($benefit) {
                    $editUrl = route('admin.benefits.edit', $benefit->id);
                    $deleteUrl = route('admin.benefits.destroy', $benefit->id);

                    return '
                        <div class="btn-group" role="group">
                            <a href="'.$editUrl.'" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                    data-url="'.$deleteUrl.'" 
                                    data-id="'.$benefit->id.'" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['icon_preview', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.benefits.index');
    }

    /**
     * Display the specified benefit.
     */
    public function show(string $id)
    {
        $benefit = $this->benefitRepository->findById((int) $id);

        if (! $benefit) {
            return redirect()->route('admin.benefits.index')
                ->with('error', 'Benefit not found.');
        }

        return view('admin.benefits.show', compact('benefit'));
    }

    public function create()
    {
        return view('admin.benefits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'benefit_name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'schema_type' => 'required|in:years,purchase',
            'schema_type_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Normalize is_active (radio or hidden + checkbox pattern)
        $validated['is_active'] = $request->boolean('is_active') ? 1 : 0;

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon');
        }

        $this->benefitRepository->create($validated);

        return redirect()->route('admin.benefits.index')
            ->with('success', 'Benefit created successfully.');
    }

    public function edit(int $id)
    {
        $benefit = $this->benefitRepository->findById($id);
        abort_unless($benefit, 404);

        return view('admin.benefits.edit', compact('benefit'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'benefit_name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'schema_type' => 'required|in:years,purchase',
            'schema_type_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active') ? 1 : 0;

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon');
        }

        $updated = $this->benefitRepository->update($id, $validated);
        abort_unless($updated, 404);

        return redirect()->route('admin.benefits.index')
            ->with('success', 'Benefit updated successfully.');
    }

    public function destroy(int $id)
    {
        $deleted = $this->benefitRepository->delete($id);
        if (request()->ajax()) {
            return response()->json(['success' => (bool) $deleted]);
        }

        return redirect()->route('admin.benefits.index')
            ->with($deleted ? 'success' : 'error', $deleted ? 'Benefit deleted successfully.' : 'Benefit delete failed.');
    }
}
