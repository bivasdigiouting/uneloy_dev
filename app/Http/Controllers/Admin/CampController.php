<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CampRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CampController extends Controller
{
    protected CampRepositoryInterface $campRepository;

    public function __construct(CampRepositoryInterface $campRepository)
    {
        $this->campRepository = $campRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->campRepository->getForDataTable();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('icon', function ($row) {
                    $url = $row->icon_url;

                    return $url ? '<img src="'.e($url).'" alt="icon" class="img-thumbnail" style="width:32px;height:32px">' : '-';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.camps.edit', $row->id);
                    $toggleUrl = route('admin.camps.toggle-status', $row->id);
                    $editBtn = '<a href="'.e($editUrl).'" class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></a>';
                    $deleteBtn = '<button data-id="'.e($row->id).'" class="btn btn-sm btn-danger delete-camp"><i class="ti ti-trash"></i></button>';
                    $toggleBtn = '<button data-id="'.e($row->id).'" data-url="'.e($toggleUrl).'" class="btn btn-sm btn-secondary toggle-camp-status"><i class="ti ti-refresh"></i></button>';

                    return $editBtn.$toggleBtn.$deleteBtn;
                })
                ->rawColumns(['icon', 'is_active', 'actions'])
                ->make(true);
        }

        return view('admin.camps.index');
    }

    public function create()
    {
        return view('admin.camps.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'camp_name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon');
        }
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $this->campRepository->create($data);

        return redirect()->route('admin.camps.index')->with('success', 'Camp created successfully');
    }

    public function edit(int $id)
    {
        $camp = $this->campRepository->findById($id);
        if (! $camp) {
            return redirect()->route('admin.camps.index')->with('error', 'Camp not found');
        }

        return view('admin.camps.edit', compact('camp'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'camp_name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon');
        }
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $updated = $this->campRepository->update($id, $data);
        if (! $updated) {
            return redirect()->back()->with('error', 'Failed to update camp');
        }

        return redirect()->route('admin.camps.index')->with('success', 'Camp updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->campRepository->delete($id);
        if (! $deleted) {
            return response()->json(['message' => 'Failed to delete camp'], 422);
        }

        return response()->json(['message' => 'Camp deleted successfully']);
    }

    public function toggleStatus(int $id)
    {
        $toggled = $this->campRepository->toggleStatus($id);
        if (! $toggled) {
            return response()->json(['message' => 'Failed to toggle status'], 422);
        }

        return response()->json(['message' => 'Status updated']);
    }
}
