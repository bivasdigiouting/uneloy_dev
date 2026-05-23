<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ECardDepartmentPermission;
use App\Models\ECardModule;
use App\Models\ECardRegistration;
use App\Models\ECardUserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ECardPermissionController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->get('department');
        // Eager-load children so view can render modules and submodules grouped
        $modules = ECardModule::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
        $perms = [];
        if ($department) {
            if (Schema::hasTable('e_card_department_permissions')) {
                $perms = ECardDepartmentPermission::where('department_level', $department)->get()->keyBy('module_id');
            } else {
                $perms = [];
            }
        }

        return view('admin.ecard_permissions.index', compact('modules', 'department', 'perms'));
    }

    public function save(Request $request)
    {
        $department = $request->input('department');
        if (! Schema::hasTable('e_card_department_permissions')) {
            return redirect()->route('admin.ecard-permissions.index', ['department' => $department])
                ->with('error', 'E-Card department permissions table is missing. Please start MySQL and run `php artisan migrate`.');
        }
        $data = $request->input('permissions', []);
        foreach ($data as $moduleId => $flags) {
            ECardDepartmentPermission::updateOrCreate(
                ['department_level' => $department, 'module_id' => (int) $moduleId],
                [
                    'can_view' => ! empty($flags['view']),
                    'can_create' => ! empty($flags['create']),
                    'can_update' => ! empty($flags['update']),
                    'can_delete' => ! empty($flags['delete']),
                ]
            );
        }

        return redirect()->route('admin.ecard-permissions.index', ['department' => $department])->with('success', 'Permissions saved');
    }

    public function syncModules()
    {
        $path = resource_path('views/ecard/_partials/sidebar.blade.php');
        if (! File::exists($path)) {
            return redirect()->back()->with('error', 'Sidebar not found');
        }
        $html = File::get($path);
        // Be flexible: match any <li> that has class starting with "treeview" (may include other classes like 'active')
        preg_match_all('/<li class=\"treeview[^\"]*\">[\s\S]*?<span>([^<]+)<\/span>[\s\S]*?<ul class=\"treeview-menu\">([\s\S]*?)<\/ul>/i', $html, $groups, PREG_SET_ORDER);
        $order = 0;
        foreach ($groups as $g) {
            $parentTitle = trim($g[1]);
            $parentKey = Str::slug($parentTitle);
            $parent = ECardModule::firstOrCreate(['key' => $parentKey], ['title' => $parentTitle, 'sort_order' => $order++]);
            // Extract sub-menu links and their display titles
            preg_match_all('/<a href=\"\{\{ route\(\'([^\']+)\'\) \}\}\">[\s\S]*?<i[^>]*><\/i>\s*([^<]+)<\/a>/i', $g[2], $links, PREG_SET_ORDER);
            $childOrder = 0;
            foreach ($links as $l) {
                $route = trim($l[1]);
                $title = trim($l[2]);
                $key = Str::slug($parentTitle.' '.$title.' '.$route);
                ECardModule::updateOrCreate(
                    ['key' => $key],
                    ['title' => $title, 'parent_id' => $parent->id, 'route_name' => $route, 'sort_order' => $childOrder++]
                );
            }
        }

        return redirect()->back()->with('success', 'Modules synced');
    }

    public function userPermissions(int $id)
    {
        $user = ECardRegistration::findOrFail($id);
        // Load parent modules with children for module/submodule-wise grouping
        $modules = ECardModule::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
        $perms = ECardUserPermission::where('ecard_registration_id', $user->id)->get()->keyBy('module_id');

        return view('admin.ecard_permissions.user', compact('user', 'modules', 'perms'));
    }

    public function saveUserPermissions(Request $request, int $id)
    {
        $user = ECardRegistration::findOrFail($id);
        $data = $request->input('permissions', []);
        foreach ($data as $moduleId => $flags) {
            ECardUserPermission::updateOrCreate(
                ['ecard_registration_id' => $user->id, 'module_id' => (int) $moduleId],
                [
                    'can_view' => ! empty($flags['view']),
                    'can_create' => ! empty($flags['create']),
                    'can_update' => ! empty($flags['update']),
                    'can_delete' => ! empty($flags['delete']),
                ]
            );
        }

        return redirect()->route('admin.ecard-permissions.user', $user->id)->with('success', 'User permissions saved');
    }
}
