<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ECardPortalEcardReportController extends Controller
{
    // Report page with filters
    public function index(Request $request)
    {
        $states = State::ordered()->get(['id', DB::raw('state_name as name')]);

        return view('ecard.users.report', compact('states'));
    }

    // Data endpoint for report table
    public function data(Request $request)
    {
        $portalUser = Auth::guard('ecard')->user();
        if (! $portalUser) {
            abort(403);
        }

        $baseRegistrations = DB::table('registrations as er')->where('er.parent_id', $portalUser->id);
        $baseEcards = DB::table('ecard_registrations as er')->where('er.parent_id', $portalUser->id);

        $registrationsFiltered = $this->applyReportFilters(clone $baseRegistrations, $request);
        $ecardsFiltered = $this->applyReportFilters(clone $baseEcards, $request);

        // DataTables server-side parameters
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);
        $length = $length > 0 ? $length : 25;

        // Ordering
        $orderColIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderMap = [
            0 => 'u.id_num',
            1 => 'u.user_id',
            2 => 'u.first_name',
            3 => 'u.email',
            4 => 'u.mobile_no',
            5 => 'u.city',
            6 => 'u.status',
            7 => 'u.created_at',
        ];
        $orderCol = $orderMap[$orderColIndex] ?? 'u.created_at';

        // Totals
        $recordsTotal = (int) ((clone $baseRegistrations)->count() + (clone $baseEcards)->count());
        $recordsFiltered = (int) ((clone $registrationsFiltered)->count() + (clone $ecardsFiltered)->count());

        $registrationsSelect = (clone $registrationsFiltered)->selectRaw(
            "'reg' as source, er.id as id_num, er.user_id, er.first_name, er.middle_name, er.last_name, COALESCE(er.gmail_id, er.email_id) as email, er.mobile_no, er.state, er.district, er.city, er.status, er.created_at"
        );

        $ecardsSelect = (clone $ecardsFiltered)->selectRaw(
            "'ecard' as source, er.id as id_num, er.user_id, er.first_name, er.middle_name, er.last_name, COALESCE(er.gmail_id, er.email_id) as email, er.mobile_no, er.state, er.district, er.city, er.status, er.created_at"
        );

        $union = $registrationsSelect->unionAll($ecardsSelect);
        $rows = DB::query()
            ->fromSub($union, 'u')
            ->orderBy($orderCol, $orderDir === 'asc' ? 'asc' : 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        // Format for frontend
        $data = $rows->map(function ($r) {
            $fullName = trim(implode(' ', array_filter([$r->first_name, $r->middle_name, $r->last_name])));
            $created = $r->created_at ? date('Y-m-d', strtotime($r->created_at)) : null;
            $token = ($r->source ?? '') === 'reg' ? 'reg-'.$r->id_num : 'ecard-'.$r->id_num;

            return [
                'id' => $token,
                'select' => (string) $token,
                'user_id' => $r->user_id,
                'name' => $fullName,
                'email' => $r->email,
                'mobile' => $r->mobile_no,
                'location' => trim(implode(', ', array_filter([$r->city, $r->district, $r->state]))),
                'status' => $r->status,
                'created' => $created,
                'print_url' => route('ecard.users.report.print', $token),
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    // Dependent dropdowns
    public function districts(Request $request)
    {
        $stateId = $request->input('state_id');
        $items = District::query()
            ->when($stateId, fn ($q) => $q->where('state_id', $stateId))
            ->ordered()
            ->get(['id', DB::raw('district_name as name')]);

        return response()->json($items);
    }

    public function cities(Request $request)
    {
        $districtId = $request->input('district_id');
        $items = City::query()
            ->when($districtId, fn ($q) => $q->where('district_id', $districtId))
            ->ordered()
            ->get(['id', DB::raw('city_name as name')]);

        return response()->json($items);
    }

    // Printable e-card
    public function printEcard($id)
    {
        $portalUser = Auth::guard('ecard')->user();
        if (! $portalUser) {
            abort(403);
        }

        $resolved = $this->resolveSourceAndId((string) $id);
        $user = null;

        if ($resolved) {
            $user = DB::table($resolved['table'].' as er')
                ->where('er.id', $resolved['id'])
                ->where('er.parent_id', $portalUser->id)
                ->first();
        } elseif (ctype_digit((string) $id)) {
            $numericId = (int) $id;
            $user = DB::table('registrations as er')
                ->where('er.id', $numericId)
                ->where('er.parent_id', $portalUser->id)
                ->first();

            if (! $user) {
                $user = DB::table('ecard_registrations as er')
                    ->where('er.id', $numericId)
                    ->where('er.parent_id', $portalUser->id)
                    ->first();
            }

            if (! $user && $numericId === (int) $portalUser->id) {
                $user = DB::table('ecard_registrations as er')
                    ->where('er.id', $numericId)
                    ->first();
            }
        }
        if (! $user) {
            abort(404);
        }
        $fullName = trim(implode(' ', array_filter([$user->first_name, $user->middle_name, $user->last_name])));
        // Attempt to fetch profile photo if present
        $photoUrl = null;
        if (! empty($user->profile_image)) {
            $photoUrl = Storage::disk('public')->url($user->profile_image);
        }
        // Derived display values
        $createdTs = $user->created_at ? strtotime($user->created_at) : time();
        $validThru = date('m/y', strtotime('+3 years', $createdTs));
        $seg1 = substr($user->mobile_no ?? '0000', 0, 4);
        $seg2 = substr((string) ($user->user_id ?? $user->id), -4);
        $seg3 = date('y', $createdTs).date('m', $createdTs);
        $seg4 = str_pad((string) ((int) ($user->id ?? 0) % 10000), 4, '0', STR_PAD_LEFT);

        return view('ecard.users.ecard_print', [
            'user' => $user,
            'fullName' => $fullName,
            'photoUrl' => $photoUrl,
            'validThru' => $validThru,
            'seg1' => $seg1,
            'seg2' => $seg2,
            'seg3' => $seg3,
            'seg4' => $seg4,
        ]);
    }

    public function printEcardBulk(Request $request)
    {
        $portalUser = Auth::guard('ecard')->user();
        if (! $portalUser) {
            abort(403);
        }

        $idsRaw = (string) $request->query('ids', '');
        $tokens = collect(explode(',', $idsRaw))
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->unique()
            ->values()
            ->take(50);

        if ($tokens->count() === 0) {
            abort(404);
        }

        $registrationsIds = [];
        $ecardsIds = [];
        $orderIndexByToken = $tokens->flip()->all();

        foreach ($tokens as $token) {
            $resolved = $this->resolveSourceAndId($token);
            if ($resolved) {
                if ($resolved['table'] === 'registrations') {
                    $registrationsIds[] = $resolved['id'];
                } elseif ($resolved['table'] === 'ecard_registrations') {
                    $ecardsIds[] = $resolved['id'];
                }

                continue;
            }

            if (! ctype_digit($token)) {
                continue;
            }

            $numericId = (int) $token;
            if ($this->rowExistsForParent('registrations', $numericId, (int) $portalUser->id)) {
                $registrationsIds[] = $numericId;

                continue;
            }
            if ($this->rowExistsForParent('ecard_registrations', $numericId, (int) $portalUser->id)) {
                $ecardsIds[] = $numericId;
            }
        }

        $registrationsIds = array_values(array_unique(array_filter($registrationsIds, fn ($v) => $v > 0)));
        $ecardsIds = array_values(array_unique(array_filter($ecardsIds, fn ($v) => $v > 0)));

        $regRows = collect();
        if (count($registrationsIds) > 0) {
            $regRows = DB::table('registrations as er')
                ->whereIn('er.id', $registrationsIds)
                ->where('er.parent_id', $portalUser->id)
                ->get()
                ->map(function ($r) {
                    $r->__token = 'reg-'.$r->id;

                    return $r;
                });
        }

        $ecardRows = collect();
        if (count($ecardsIds) > 0) {
            $ecardRows = DB::table('ecard_registrations as er')
                ->whereIn('er.id', $ecardsIds)
                ->where('er.parent_id', $portalUser->id)
                ->get()
                ->map(function ($r) {
                    $r->__token = 'ecard-'.$r->id;

                    return $r;
                });
        }

        $rows = $regRows->merge($ecardRows)->values()->sortBy(function ($r) use ($orderIndexByToken) {
            return $orderIndexByToken[$r->__token] ?? PHP_INT_MAX;
        })->values();

        if ($rows->count() === 0) {
            abort(404);
        }

        return view('ecard.users.ecard_print_bulk', [
            'users' => $rows,
            'autoprint' => $request->boolean('autoprint'),
        ]);
    }

    private function applyReportFilters($query, Request $request)
    {
        if ($request->filled('from_date')) {
            $query->whereDate('er.created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('er.created_at', '<=', $request->input('to_date'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['active', 'inactive'], true)) {
            $query->whereRaw('LOWER(er.status) = ?', [$request->input('status')]);
        }

        if ($request->filled('state')) {
            $query->where('er.state', $request->input('state'));
        }
        if ($request->filled('district')) {
            $query->where('er.district', $request->input('district'));
        }
        if ($request->filled('city')) {
            $query->where('er.city', $request->input('city'));
        }

        if ($request->filled('name')) {
            $name = '%'.strtolower($request->input('name')).'%';
            $query->whereRaw('LOWER(CONCAT_WS(" ", er.first_name, er.middle_name, er.last_name)) LIKE ?', [$name]);
        }
        if ($request->filled('email')) {
            $email = '%'.strtolower($request->input('email')).'%';
            $query->whereRaw('LOWER(COALESCE(er.gmail_id, er.email_id)) LIKE ?', [$email]);
        }
        if ($request->filled('mobile')) {
            $query->where('er.mobile_no', 'like', '%'.$request->input('mobile').'%');
        }

        return $query;
    }

    private function resolveSourceAndId(string $token): ?array
    {
        $t = trim($token);
        if ($t === '') {
            return null;
        }

        if (Str::startsWith($t, 'reg-')) {
            $id = substr($t, 4);
            if (ctype_digit($id) && (int) $id > 0) {
                return ['table' => 'registrations', 'id' => (int) $id];
            }
        }

        if (Str::startsWith($t, 'ecard-')) {
            $id = substr($t, 6);
            if (ctype_digit($id) && (int) $id > 0) {
                return ['table' => 'ecard_registrations', 'id' => (int) $id];
            }
        }

        return null;
    }

    private function rowExistsForParent(string $table, int $id, int $parentId): bool
    {
        return DB::table($table.' as er')
            ->where('er.id', $id)
            ->where('er.parent_id', $parentId)
            ->exists();
    }
}
