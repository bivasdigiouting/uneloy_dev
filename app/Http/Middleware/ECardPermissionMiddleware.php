<?php

namespace App\Http\Middleware;

use App\Helpers\ECardPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ECardPermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('ecard')->user();
        
        // KYC Check for non-customer users
        if ($user && !in_array(strtolower((string) $user->department_level), ['customer', 'member'], true)) {
            if ($user->kyc_status !== 'approved') {
                $route = $request->route();
                $name = $route ? $route->getName() : null;

                // Exempt dashboard, logout, profile and other basic routes from KYC block
                $exemptRoutes = [
                    'ecard.dashboard', 
                    'ecard.logout', 
                    // Add any other routes like profile updates if needed
                ];

                if ($name && !in_array($name, $exemptRoutes)) {
                    return redirect()->route('ecard.dashboard')
                        ->with('error', 'Your kyc has been not yet been approved, so you can not access this page');
                }
            }
        }

        $route = $request->route();
        $name = $route ? $route->getName() : null;
        if (! $name) {
            return $next($request);
        }
        $method = strtolower($request->getMethod());
        $allowed = false;
        if ($method === 'get') {
            $allowed = ECardPermission::canView($name);
        } elseif ($method === 'post') {
            $allowed = ECardPermission::canCreate($name);
        } elseif ($method === 'put' || $method === 'patch') {
            $allowed = ECardPermission::canUpdate($name);
        } elseif ($method === 'delete') {
            $allowed = ECardPermission::canDelete($name);
        }
        if (! $allowed) {
            abort(403);
        }

        return $next($request);
    }
}
