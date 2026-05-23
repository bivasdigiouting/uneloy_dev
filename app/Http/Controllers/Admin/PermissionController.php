<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    protected PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = $this->permissionRepository->withRoles();

            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('roles', function ($permission) {
                    $roles = $permission->roles->pluck('display_name')->toArray();

                    return implode(', ', array_slice($roles, 0, 3)).
                           (count($roles) > 3 ? ' (+'.(count($roles) - 3).' more)' : '');
                })
                ->addColumn('status', function ($permission) {
                    return $permission->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($permission) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.permissions.show', $permission->id).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.permissions.edit', $permission->id).'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-permission" data-id="'.$permission->id.'"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = $this->permissionRepository->getModules();

        return view('admin.permissions.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $permissionData = $request->only(['name', 'display_name', 'description', 'module']);
        $permissionData['is_active'] = $request->has('is_active');

        $this->permissionRepository->create($permissionData);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = $this->permissionRepository->find($id);

        if (! $permission) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Permission not found.');
        }

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = $this->permissionRepository->find($id);

        if (! $permission) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Permission not found.');
        }

        $modules = $this->permissionRepository->getModules();

        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = $this->permissionRepository->find($id);

        if (! $permission) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Permission not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,'.$id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $permissionData = $request->only(['name', 'display_name', 'description', 'module']);
        $permissionData['is_active'] = $request->has('is_active');

        $this->permissionRepository->update($id, $permissionData);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = $this->permissionRepository->find($id);

        if (! $permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.',
            ], 404);
        }

        $deleted = $this->permissionRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete permission.',
        ], 500);
    }

    /**
     * Handle bulk actions on permissions
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $action = $request->input('action');
        $ids = $request->input('ids');
        $count = 0;

        try {
            switch ($action) {
                case 'activate':
                    foreach ($ids as $id) {
                        $this->permissionRepository->update($id, ['is_active' => true]);
                        $count++;
                    }
                    $message = "Successfully activated {$count} permission(s).";
                    break;

                case 'deactivate':
                    foreach ($ids as $id) {
                        $this->permissionRepository->update($id, ['is_active' => false]);
                        $count++;
                    }
                    $message = "Successfully deactivated {$count} permission(s).";
                    break;

                case 'delete':
                    foreach ($ids as $id) {
                        $this->permissionRepository->delete($id);
                        $count++;
                    }
                    $message = "Successfully deleted {$count} permission(s).";
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid action selected.');
            }

            return redirect()->route('admin.permissions.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while processing the bulk action: '.$e->getMessage());
        }
    }
}
