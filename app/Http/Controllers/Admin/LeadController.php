<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    protected LeadRepositoryInterface $leadRepository;

    public function __construct(LeadRepositoryInterface $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function index(Request $request)
    {
        return view('admin.leads.index');
    }

    public function data(Request $request)
    {
        $query = $this->leadRepository->getForDataTable();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '-';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('admin.leads.edit', $row->id);
                $toggleUrl = route('admin.leads.toggle-status', $row->id);
                $editBtn = '<a href="'.e($editUrl).'" class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></a>';
                $deleteBtn = '<button data-id="'.e($row->id).'" class="btn btn-sm btn-danger delete-lead"><i class="ti ti-trash"></i></button>';
                $toggleBtn = '<button data-id="'.e($row->id).'" data-url="'.e($toggleUrl).'" class="btn btn-sm btn-secondary toggle-lead-status"><i class="ti ti-refresh"></i></button>';

                return $editBtn.$toggleBtn.$deleteBtn;
            })
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.leads.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $this->leadRepository->create($data);

        return redirect()->route('admin.leads.index')->with('success', 'Lead created successfully');
    }

    public function edit(int $id)
    {
        $lead = $this->leadRepository->findById($id);
        if (! $lead) {
            return redirect()->route('admin.leads.index')->with('error', 'Lead not found');
        }

        return view('admin.leads.edit', compact('lead'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'lead_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $updated = $this->leadRepository->update($id, $data);
        if (! $updated) {
            return redirect()->route('admin.leads.index')->with('error', 'Lead update failed');
        }

        return redirect()->route('admin.leads.index')->with('success', 'Lead updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->leadRepository->delete($id);
        if (! $deleted) {
            return response()->json(['message' => 'Delete failed'], 404);
        }

        return response()->json(['message' => 'Deleted']);
    }

    public function toggleStatus(int $id)
    {
        $toggled = $this->leadRepository->toggleStatus($id);
        if (! $toggled) {
            return response()->json(['message' => 'Toggle failed'], 404);
        }

        return response()->json(['message' => 'Status updated']);
    }
}
