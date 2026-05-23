<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SocialMediaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SocialMediaController extends Controller
{
    protected SocialMediaRepositoryInterface $socialMediaRepository;

    public function __construct(SocialMediaRepositoryInterface $socialMediaRepository)
    {
        $this->socialMediaRepository = $socialMediaRepository;
    }

    public function index(Request $request)
    {
        return view('admin.social_media.index');
    }

    public function data(Request $request)
    {
        $query = $this->socialMediaRepository->getForDataTable();

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
                $editUrl = route('admin.social-media.edit', $row->id);
                $toggleUrl = route('admin.social-media.toggle-status', $row->id);
                $editBtn = '<a href="'.e($editUrl).'" class="btn btn-sm btn-warning me-1"><i class="ti ti-edit"></i></a>';
                $deleteBtn = '<button data-id="'.e($row->id).'" class="btn btn-sm btn-danger delete-social-media"><i class="ti ti-trash"></i></button>';
                $toggleBtn = '<button data-id="'.e($row->id).'" data-url="'.e($toggleUrl).'" class="btn btn-sm btn-secondary toggle-social-media-status"><i class="ti ti-refresh"></i></button>';

                return $editBtn.$toggleBtn.$deleteBtn;
            })
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.social_media.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_media_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $this->socialMediaRepository->create($data);

        return redirect()->route('admin.social-media.index')->with('success', 'Social Media created successfully');
    }

    public function edit(int $id)
    {
        $socialMedia = $this->socialMediaRepository->findById($id);
        if (! $socialMedia) {
            return redirect()->route('admin.social-media.index')->with('error', 'Social Media not found');
        }

        return view('admin.social_media.edit', compact('socialMedia'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'social_media_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $updated = $this->socialMediaRepository->update($id, $data);
        if (! $updated) {
            return redirect()->back()->with('error', 'Failed to update Social Media');
        }

        return redirect()->route('admin.social-media.index')->with('success', 'Social Media updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->socialMediaRepository->delete($id);
        if (! $deleted) {
            return response()->json(['message' => 'Failed to delete Social Media'], 422);
        }

        return response()->json(['message' => 'Social Media deleted successfully']);
    }

    public function toggleStatus(int $id)
    {
        $toggled = $this->socialMediaRepository->toggleStatus($id);
        if (! $toggled) {
            return response()->json(['message' => 'Failed to toggle status'], 422);
        }

        return response()->json(['message' => 'Status updated']);
    }
}
