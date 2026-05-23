<?php

namespace App\Http\Middleware;

use App\Models\WebsiteSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->is('admin') ||
            $request->is('admin/*') ||
            $request->is('build/*') ||
            $request->is('backend-assets/*') ||
            $request->is('frontend-assets/*') ||
            $request->is('storage/*') ||
            $request->is('api/*') ||
            $request->is('up')
        ) {
            return $next($request);
        }

        try {
            $settings = WebsiteSettings::getSettings();
        } catch (\Throwable $e) {
            return $next($request);
        }

        if (! $settings || ! $settings->maintenance_mode) {
            return $next($request);
        }

        return response()
            ->view('frontend.maintenance', ['settings' => $settings], 503);
    }
}
