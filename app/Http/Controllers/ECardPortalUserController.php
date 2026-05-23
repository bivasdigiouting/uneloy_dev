<?php

namespace App\Http\Controllers;

use App\Models\ECardRegistration;
use App\Models\Registration;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ECardPortalUserController extends Controller
{
    private function isVillageMember(?ECardRegistration $user): bool
    {
        $departmentLevel = Str::lower(trim((string) ($user->department_level ?? '')));

        return $departmentLevel === Str::lower('Village Level Member')
            || $departmentLevel === 'village_level'
            || Str::contains($departmentLevel, 'village');
    }

    private function resolveUserRecord(ECardRegistration $user, int $id): Registration|ECardRegistration
    {
        if ($this->isVillageMember($user)) {
            return Registration::query()
                ->where('parent_id', $user->id)
                ->whereKey($id)
                ->firstOrFail();
        }

        return ECardRegistration::query()
            ->where('parent_id', $user->id)
            ->whereKey($id)
            ->firstOrFail();
    }

    private function normalizeNullableString(mixed $value): ?string
    {
        $v = trim((string) ($value ?? ''));

        return $v === '' ? null : $v;
    }

    public function index(Request $request)
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return redirect()->route('ecard.login');
        }

        $isVillageMember = $this->isVillageMember($user);

        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(10, min(100, $perPage));

        $query = $isVillageMember ? Registration::query() : ECardRegistration::query();
        $query->where('parent_id', $user->id);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('user_id', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('middle_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('mobile_no', 'like', "%{$q}%")
                    ->orWhere('email_id', 'like', "%{$q}%")
                    ->orWhere('business_name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('district', 'like', "%{$q}%")
                    ->orWhere('state', 'like', "%{$q}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $records = $query
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $source = $isVillageMember ? 'registrations' : 'ecard_registrations';

        $planNameByRegistrationId = [];
        $userTypeByRegistrationId = [];

        if ($isVillageMember) {
            $registrationIds = $records->getCollection()
                ->pluck('id')
                ->filter()
                ->values()
                ->all();

            if (count($registrationIds) > 0) {
                $txs = WalletTransaction::query()
                    ->whereIn('registration_id', $registrationIds)
                    ->where('transaction_type', 'add')
                    ->where('narration', 'like', 'First recharge credit%')
                    ->orderBy('id')
                    ->get(['registration_id', 'narration']);

                foreach ($txs as $t) {
                    $rid = (int) $t->registration_id;
                    if (isset($planNameByRegistrationId[$rid])) {
                        continue;
                    }
                    $narration = (string) ($t->narration ?? '');
                    $plan = null;
                    if (preg_match('/First recharge credit \\((.*?)\\)/', $narration, $m)) {
                        $plan = trim((string) ($m[1] ?? ''));
                    }
                    $planNameByRegistrationId[$rid] = $plan ?: null;
                    $userTypeByRegistrationId[$rid] = 'paid';
                }

                foreach ($registrationIds as $rid) {
                    if (! isset($userTypeByRegistrationId[(int) $rid])) {
                        $userTypeByRegistrationId[(int) $rid] = 'free';
                    }
                }
            }
        }

        return view('ecard.users.my', compact('user', 'records', 'source', 'planNameByRegistrationId', 'userTypeByRegistrationId'));
    }

    public function update(Request $request, int $id)
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return redirect()->route('ecard.login');
        }

        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'mobile_no' => ['nullable', 'string', 'max:20'],
            'email_id' => ['nullable', 'email', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],
        ]);

        $record = $this->resolveUserRecord($user, $id);
        $table = $record->getTable();

        $payload = [];
        foreach (array_keys($validated) as $key) {
            if (! Schema::hasColumn($table, $key)) {
                continue;
            }
            $payload[$key] = $this->normalizeNullableString($validated[$key] ?? null);
        }

        if (count($payload) > 0) {
            $record->fill($payload);
            $record->save();
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $user = Auth::guard('ecard')->user();
        if (! $user) {
            return redirect()->route('ecard.login');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $record = $this->resolveUserRecord($user, $id);

        $newStatus = $validated['status'] === 'approved' ? 'approved' : 'rejected';
        if (! ($record instanceof Registration)) {
            $newStatus = $validated['status'] === 'approved' ? 'active' : 'rejected';
        }

        $record->status = $newStatus;
        $record->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
