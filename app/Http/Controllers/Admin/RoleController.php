<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected RoleRepositoryInterface $roleRepository;

    protected PermissionRepositoryInterface $permissionRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = $this->roleRepository->withPermissions();

            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('permissions', function ($role) {
                    $permissions = $role->permissions->pluck('display_name')->toArray();

                    return implode(', ', array_slice($permissions, 0, 3)).
                           (count($permissions) > 3 ? ' (+'.(count($permissions) - 3).' more)' : '');
                })
                ->addColumn('status', function ($role) {
                    return $role->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($role) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.roles.show', $role->id).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.roles.edit', $role->id).'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-role" data-id="'.$role->id.'"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $roles = $this->roleRepository->getActive();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = $this->permissionRepository->getActive()->groupBy('module');

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $roleData = $request->only(['name', 'display_name', 'description']);
        $roleData['is_active'] = $request->has('is_active');

        $role = $this->roleRepository->create($roleData);

        if ($request->has('permissions')) {
            $this->roleRepository->syncPermissions($role->id, $request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->roleRepository->find($id);

        if (! $role) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role not found.');
        }

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = $this->roleRepository->find($id);

        if (! $role) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role not found.');
        }

        $permissions = $this->permissionRepository->getActive()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = $this->roleRepository->find($id);

        if (! $role) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $roleData = $request->only(['name', 'display_name', 'description']);
        $roleData['is_active'] = $request->has('is_active');

        $this->roleRepository->update($id, $roleData);

        if ($request->has('permissions')) {
            $this->roleRepository->syncPermissions($id, $request->permissions);
        } else {
            $this->roleRepository->syncPermissions($id, []);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = $this->roleRepository->find($id);

        if (! $role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        $deleted = $this->roleRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete role.',
        ], 500);
    }
}
