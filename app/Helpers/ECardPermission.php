<?php

namespace App\Helpers;

use App\Models\ECardDepartmentPermission;
use App\Models\ECardModule;
use App\Models\ECardUserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ECardPermission
{
    public static function canView(string $routeName): bool
    {
        return self::check($routeName, 'view');
    }

    public static function canCreate(string $routeName): bool
    {
        return self::check($routeName, 'create');
    }

    public static function canUpdate(string $routeName): bool
    {
        return self::check($routeName, 'update');
    }

    public static function canDelete(string $routeName): bool
    {
        return self::check($routeName, 'delete');
    }

    private static function check(string $routeName, string $action): bool
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return false;
        }
        $module = ECardModule::where('route_name', $routeName)->first();
        if (! $module) {
            return true;
        }
        if (! Schema::hasTable('e_card_user_permissions') || ! Schema::hasTable('e_card_department_permissions')) {
            // If permission tables aren't initialized yet, default to allowing view
            // and disallowing create/update/delete to be safe.
            if ($action === 'view') {
                return true;
            }

            return false;
        }
        $userPerm = ECardUserPermission::where('ecard_registration_id', $user->id)->where('module_id', $module->id)->first();
        if ($userPerm) {
            return (bool) $userPerm->{'can_'.$action};
        }
        $deptPerm = ECardDepartmentPermission::where('department_level', $user->department_level)->where('module_id', $module->id)->first();
        if ($deptPerm) {
            return (bool) $deptPerm->{'can_'.$action};
        }

        return false;
    }
}
