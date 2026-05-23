<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoginHistoryReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.login-history.index');
    }

    public function data(Request $request)
    {
        // Base table
        $table = Schema::hasTable('user_login_histories') ? 'user_login_histories' : null;
        if (! $table) {
            return response()->json([
                'draw' => (int) ($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'summary' => ['count' => 0],
                'message' => 'Login history table not found.',
            ]);
        }

        // Build query with optional join to registrations and users for user info
        $query = DB::table($table.' as h');

        $selects = [
            DB::raw('h.id'),
            DB::raw('h.registration_id'),
            DB::raw('h.user_id'),
            DB::raw('h.platform'),
            DB::raw('h.ip_address'),
            DB::raw('h.user_agent'),
            DB::raw('h.logged_in_at'),
            DB::raw('h.logged_out_at'),
        ];

        // Join registration to enrich user details
        if (Schema::hasTable('registrations')) {
            $query->leftJoin('registrations as r', 'r.id', '=', 'h.registration_id');
            foreach (['user_id', 'mobile_no', 'email_id', 'department_level', 'first_name', 'middle_name', 'last_name'] as $col) {
                if (Schema::hasColumn('registrations', $col)) {
                    $selects[] = DB::raw("r.$col");
                }
            }
        }

        // Join users to enrich staff/admin user details
        if (Schema::hasTable('users')) {
            $query->leftJoin('users as u', 'u.id', '=', 'h.user_id');
            if (Schema::hasColumn('users', 'name')) {
                $selects[] = DB::raw('u.name as u_name');
            }
            if (Schema::hasColumn('users', 'email')) {
                $selects[] = DB::raw('u.email as u_email');
            }
        }

        $query->select($selects);

        // Filters
        $entityType = $request->input('entity_type');
        if ($entityType === 'registration') {
            $query->whereNotNull('h.registration_id');
        } elseif ($entityType === 'user') {
            $query->whereNotNull('h.user_id');
        }
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('h.logged_in_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('h.logged_in_at', '<=', $endDate);
        }

        if ($request->filled('platform')) {
            $query->where('h.platform', strtolower($request->input('platform')));
        }
        if ($request->filled('user_id') && Schema::hasColumn('registrations', 'user_id')) {
            $query->where('r.user_id', $request->input('user_id'));
        }
        if ($request->filled('mobile') && Schema::hasColumn('registrations', 'mobile_no')) {
            $query->where('r.mobile_no', 'like', '%'.trim($request->input('mobile')).'%');
        }
        if ($request->filled('ip')) {
            $query->where('h.ip_address', 'like', '%'.trim($request->input('ip')).'%');
        }

        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $query->where(function ($q) use ($term) {
                $q->orWhere('h.ip_address', 'like', $term)
                    ->orWhere('h.platform', 'like', $term)
                    ->orWhere('h.user_agent', 'like', $term);
                if (Schema::hasTable('registrations')) {
                    foreach (['r.user_id', 'r.mobile_no', 'r.email_id', 'r.first_name', 'r.middle_name', 'r.last_name'] as $col) {
                        $q->orWhere($col, 'like', $term);
                    }
                }
                if (Schema::hasTable('users')) {
                    foreach (['u.name', 'u.email'] as $col) {
                        $q->orWhere($col, 'like', $term);
                    }
                }
            });
        }

        $query->orderBy('h.logged_in_at', 'desc');

        // DataTables pagination
        $length = (int) ($request->input('length', 10));
        $start = (int) ($request->input('start', 0));
        $page = (int) floor($start / max($length, 1)) + 1;
        $paginator = $query->paginate($length > 0 ? $length : 10, ['*'], 'page', $page);
        $rows = $paginator->items();

        // Summary: count of sessions matching filters
        $summaryQuery = DB::table($table.' as h');
        if (Schema::hasTable('registrations')) {
            $summaryQuery->leftJoin('registrations as r', 'r.id', '=', 'h.registration_id');
        }
        if (Schema::hasTable('users')) {
            $summaryQuery->leftJoin('users as u', 'u.id', '=', 'h.user_id');
        }
        $summaryQuery->selectRaw('COUNT(*) as count');
        if ($entityType === 'registration') {
            $summaryQuery->whereNotNull('h.registration_id');
        } elseif ($entityType === 'user') {
            $summaryQuery->whereNotNull('h.user_id');
        }
        if ($startDate) {
            $summaryQuery->whereDate('h.logged_in_at', '>=', $startDate);
        }
        if ($endDate) {
            $summaryQuery->whereDate('h.logged_in_at', '<=', $endDate);
        }
        if ($request->filled('platform')) {
            $summaryQuery->where('h.platform', strtolower($request->input('platform')));
        }
        if ($request->filled('user_id') && Schema::hasColumn('registrations', 'user_id')) {
            $summaryQuery->where('r.user_id', $request->input('user_id'));
        }
        if ($request->filled('mobile') && Schema::hasColumn('registrations', 'mobile_no')) {
            $summaryQuery->where('r.mobile_no', 'like', '%'.trim($request->input('mobile')).'%');
        }
        if ($request->filled('ip')) {
            $summaryQuery->where('h.ip_address', 'like', '%'.trim($request->input('ip')).'%');
        }
        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $summaryQuery->where(function ($q) use ($term) {
                $q->orWhere('h.ip_address', 'like', $term)
                    ->orWhere('h.platform', 'like', $term)
                    ->orWhere('h.user_agent', 'like', $term);
                if (Schema::hasTable('registrations')) {
                    foreach (['r.user_id', 'r.mobile_no', 'r.email_id', 'r.first_name', 'r.middle_name', 'r.last_name'] as $col) {
                        $q->orWhere($col, 'like', $term);
                    }
                }
                if (Schema::hasTable('users')) {
                    foreach (['u.name', 'u.email'] as $col) {
                        $q->orWhere($col, 'like', $term);
                    }
                }
            });
        }
        $summary = $summaryQuery->first();

        $data = array_map(function ($row) {
            $nameReg = trim(($row->first_name ?? '').' '.($row->middle_name ?? '').' '.($row->last_name ?? ''));
            $name = $nameReg ?: ($row->u_name ?? null);
            $duration = null;
            if (! empty($row->logged_in_at) && ! empty($row->logged_out_at)) {
                try {
                    $start = \Carbon\Carbon::parse($row->logged_in_at);
                    $end = \Carbon\Carbon::parse($row->logged_out_at);
                    $seconds = max($end->diffInSeconds($start), 0);
                    $duration = gmdate('H:i:s', $seconds);
                } catch (\Throwable $e) {
                    $duration = null;
                }
            }

            return [
                'login_time' => isset($row->logged_in_at) ? (string) $row->logged_in_at : null,
                'logout_time' => isset($row->logged_out_at) ? (string) $row->logged_out_at : '—',
                'duration' => $duration,
                'platform' => $row->platform ?? null,
                'ip_address' => $row->ip_address ?? null,
                'user_id' => $row->user_id ?? ($row->r_user_id ?? null),
                'name' => $name ?: null,
                'mobile' => $row->mobile_no ?? null,
            ];
        }, $rows);

        return response()->json([
            'draw' => (int) ($request->input('draw', 1)),
            'recordsTotal' => (int) $paginator->total(),
            'recordsFiltered' => (int) $paginator->total(),
            'data' => $data,
            'summary' => [
                'count' => (int) ($summary->count ?? 0),
            ],
        ]);
    }
}
