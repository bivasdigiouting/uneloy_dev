<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdvertisementRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdvertisementController extends Controller
{
    protected AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    public function index(Request $request)
    {
        return view('admin.advertisements.index');
    }

    public function data(Request $request)
    {
        $query = $this->advertisementRepository->getForDataTable();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('price_per_day', function ($row) {
                return number_format((float) $row->price_per_day, 2);
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
                $editUrl = route('admin.advertisements.edit', $row->id);
                $toggleUrl = route('admin.advertisements.toggle-status', $row->id);
                $editBtn = '<a href="'.e($editUrl).'" class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></a>';
                $deleteBtn = '<button data-id="'.e($row->id).'" class="btn btn-sm btn-danger delete-advertisement"><i class="ti ti-trash"></i></button>';
                $toggleBtn = '<button data-id="'.e($row->id).'" data-url="'.e($toggleUrl).'" class="btn btn-sm btn-secondary toggle-advertisement-status"><i class="ti ti-refresh"></i></button>';

                return $editBtn.$toggleBtn.$deleteBtn;
            })
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $this->advertisementRepository->create($data);

        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement created successfully');
    }

    public function edit(int $id)
    {
        $advertisement = $this->advertisementRepository->findById($id);
        if (! $advertisement) {
            return redirect()->route('admin.advertisements.index')->with('error', 'Advertisement not found');
        }

        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $updated = $this->advertisementRepository->update($id, $data);
        if (! $updated) {
            return redirect()->back()->with('error', 'Failed to update advertisement');
        }

        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->advertisementRepository->delete($id);
        if (! $deleted) {
            return response()->json(['message' => 'Failed to delete advertisement'], 422);
        }

        return response()->json(['message' => 'Advertisement deleted successfully']);
    }

    public function toggleStatus(int $id)
    {
        $toggled = $this->advertisementRepository->toggleStatus($id);
        if (! $toggled) {
            return response()->json(['message' => 'Failed to toggle status'], 422);
        }

        return response()->json(['message' => 'Status updated']);
    }
}
