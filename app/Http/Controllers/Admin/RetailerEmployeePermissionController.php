<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RetailerEmployeePermissionController extends Controller
{
    /**
     * Display the Retailer/Employee Permission index page.
     */
    public function index(Request $request)
    {
        // Fetch roles and permission modules for display. Keep it lightweight for now.
        $roles = Role::where('guard_name', 'web')->orderBy('display_name')->get();
        $permissions = Permission::where('guard_name', 'web')->orderBy('module')->orderBy('display_name')->get();
        $commissionLevels = [
            'State e-Card Seva',
            'District e-Card Seva',
            'Block - e-Card Seva',
            'G P M e-Card Seva',
            'e-Card Seva',
            'Member',
        ];

        return view('admin.retailer-employee-permissions.index', compact('roles', 'permissions', 'commissionLevels'));
    }

    /**
     * Get permissions assigned to a role.
     */
    public function rolePermissions(Role $role)
    {
        // Ensure we work only with web guard roles
        if ($role->guard_name !== 'web') {
            return response()->json(['success' => false, 'message' => 'Invalid guard for role'], 422);
        }

        $assigned = $role->permissions()->pluck('id');

        return response()->json([
            'success' => true,
            'permission_ids' => $assigned,
        ]);
    }

    /**
     * Assign permissions to a role (sync).
     */
    public function assignPermissions(Request $request, Role $role)
    {
        if ($role->guard_name !== 'web') {
            return response()->json(['success' => false, 'message' => 'Invalid guard for role'], 422);
        }

        $validated = $request->validate([
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permission_ids'] ?? [];

        // Filter permissions to matching guard
        $guardPermissionIds = Permission::whereIn('id', $permissionIds)
            ->where('guard_name', $role->guard_name)
            ->pluck('id')
            ->all();

        // Sync permissions
        $role->syncPermissions($guardPermissionIds);

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated successfully',
        ]);
    }
}
