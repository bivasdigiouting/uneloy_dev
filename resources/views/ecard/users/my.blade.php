@extends('ecard.ecard')
@section('title', 'My Registered Users')
@section('content')
<section class="content">
    <div class="content-inner">
        <div class="container-fluid py-3">
            <div class="card p-4">
                @if(session('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="card-title mb-1"><i class="fas fa-users me-2"></i>My Registered Users</h5>
                        <div class="text-muted small">Users registered by you</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-info">Source: {{ $source === 'registrations' ? 'Customers' : 'E-Card Users' }}</span>
                        <a class="btn btn-sm btn-primary" href="{{ route('ecard.registration.create') }}">
                            <i class="fas fa-user-plus me-1"></i> New Registration
                        </a>
                    </div>
                </div>

                <form method="GET" action="{{ route('ecard.users.my') }}" class="row g-2 align-items-end mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="User ID, name, mobile, email, city...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All</option>
                            @if($source === 'registrations')
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            @else
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Approved</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Per page</label>
                        <select class="form-select" name="per_page">
                            @foreach([10,25,50,100] as $n)
                                <option value="{{ $n }}" {{ (int) request('per_page', 25) === $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                        <a href="{{ route('ecard.users.my') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                @if($source === 'registrations')
                                    <th>Plan Name</th>
                                    <th>User Type</th>
                                @endif
                                <th>Mobile</th>
                                <th>Email</th>
                                @if($source !== 'registrations')
                                    <th>Business</th>
                                @endif
                                <th>Location</th>
                                <th>Area</th>
                                <th>Pin</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $maskMiddle = function ($value, int $keepStart = 2, int $keepEnd = 2): string {
                                    $value = trim((string) ($value ?? ''));
                                    if ($value === '') {
                                        return '—';
                                    }

                                    $len = strlen($value);
                                    if ($len <= ($keepStart + $keepEnd)) {
                                        return 'xxxxx';
                                    }

                                    return substr($value, 0, $keepStart) . 'xxxxx' . substr($value, -$keepEnd);
                                };

                                $maskEmail = function ($value) use ($maskMiddle): string {
                                    $value = trim((string) ($value ?? ''));
                                    if ($value === '') {
                                        return '—';
                                    }
                                    if (! str_contains($value, '@')) {
                                        return $maskMiddle($value, 2, 2);
                                    }

                                    [$local, $domain] = array_pad(explode('@', $value, 2), 2, '');
                                    $local = trim((string) $local);
                                    $domain = trim((string) $domain);

                                    $localMasked = strlen($local) >= 4
                                        ? (substr($local, 0, 2) . 'xxxxx' . substr($local, -1))
                                        : $maskMiddle($local, 1, 0);

                                    $dotPos = strrpos($domain, '.');
                                    if ($dotPos === false) {
                                        $domainMasked = $maskMiddle($domain, 1, 0);
                                    } else {
                                        $name = substr($domain, 0, $dotPos);
                                        $tld = substr($domain, $dotPos);
                                        $nameMasked = strlen($name) >= 3
                                            ? (substr($name, 0, 1) . 'xxxxx' . substr($name, -1))
                                            : $maskMiddle($name, 1, 0);
                                        $domainMasked = $nameMasked . $tld;
                                    }

                                    return $localMasked . '@' . $domainMasked;
                                };
                            @endphp
                            @forelse($records as $r)
                                @php
                                    $fullName = trim(implode(' ', array_filter([$r->first_name ?? null, $r->middle_name ?? null, $r->last_name ?? null])));
                                    $location = trim(implode(', ', array_filter([$r->city ?? null, $r->district ?? null, $r->state ?? null])));
                                    $status = strtolower((string) ($r->status ?? ''));
                                    $statusForUi = $source === 'ecard_registrations' && $status === 'active' ? 'approved' : $status;
                                    $statusClass = match ($status) {
                                        'active', 'approved' => 'bg-success',
                                        'inactive' => 'bg-secondary',
                                        'pending' => 'bg-warning text-dark',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                    };
                                    $isCustomer = strtolower((string) ($r->department_level ?? '')) === 'customer';
                                    $planName = $isCustomer ? ($planNameByRegistrationId[$r->id] ?? null) : null;
                                    $userType = $isCustomer ? ($userTypeByRegistrationId[$r->id] ?? null) : null;
                                    $userTypeLabel = $userType === 'paid' ? 'Paid' : ($userType === 'free' ? 'Free' : '—');
                                    $userTypeClass = $userType === 'paid' ? 'bg-primary' : ($userType === 'free' ? 'bg-secondary' : 'bg-light text-dark');
                                @endphp
                                <tr>
                                    <td style="min-width:240px;">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary btnEditUser"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal"
                                                data-id="{{ $r->id }}"
                                                data-first_name="{{ e($r->first_name ?? '') }}"
                                                data-middle_name="{{ e($r->middle_name ?? '') }}"
                                                data-last_name="{{ e($r->last_name ?? '') }}"
                                                data-mobile_no="{{ e($r->mobile_no ?? '') }}"
                                                data-email_id="{{ e($r->email_id ?? '') }}"
                                                data-business_name="{{ e($r->business_name ?? '') }}"
                                                data-state="{{ e($r->state ?? '') }}"
                                                data-district="{{ e($r->district ?? '') }}"
                                                data-city="{{ e($r->city ?? '') }}"
                                                data-area="{{ e($r->area ?? '') }}"
                                                data-pin_code="{{ e($r->pin_code ?? '') }}"
                                            >
                                                Edit
                                            </button>

                                            @if($statusForUi === 'approved')
                                                <form method="POST" action="{{ route('ecard.users.my.status', $r->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                                </form>
                                            @elseif($statusForUi === 'rejected')
                                                <form method="POST" action="{{ route('ecard.users.my.status', $r->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('ecard.users.my.status', $r->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('ecard.users.my.status', $r->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="fw-semibold">{{ $maskMiddle($r->user_id ?? null, 2, 2) }}</td>
                                    <td>{{ $fullName !== '' ? $fullName : '—' }}</td>
                                    <td>{{ $r->department_level ?? '—' }}</td>
                                    @if($source === 'registrations')
                                        <td>{{ $isCustomer ? ($planName ?: '—') : '—' }}</td>
                                        <td>
                                            @if($isCustomer)
                                                <span class="badge {{ $userTypeClass }}">{{ $userTypeLabel }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $maskMiddle($r->mobile_no ?? null, 2, 2) }}</td>
                                    <td>{{ $maskEmail($r->email_id ?? null) }}</td>
                                    @if($source !== 'registrations')
                                        <td>{{ $r->business_name ?? '—' }}</td>
                                    @endif
                                    <td>{{ $location !== '' ? $location : '—' }}</td>
                                    <td>{{ $r->area ?? '—' }}</td>
                                    <td>{{ $r->pin_code ?? '—' }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ $statusForUi !== '' ? ucfirst($statusForUi) : '—' }}</span></td>
                                    <td>{{ optional($r->created_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $source === 'registrations' ? 13 : 12 }}" class="text-center text-muted py-4">No registered users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                    <div class="text-muted small">
                        Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }} users
                    </div>
                    <div>
                        {{ $records->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="edit_first_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" id="edit_middle_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="edit_last_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile</label>
                            <input type="text" class="form-control" name="mobile_no" id="edit_mobile_no">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email_id" id="edit_email_id">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Business Name</label>
                            <input type="text" class="form-control" name="business_name" id="edit_business_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" name="state" id="edit_state">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">District</label>
                            <input type="text" class="form-control" name="district" id="edit_district">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" id="edit_city">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Area</label>
                            <input type="text" class="form-control" name="area" id="edit_area">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pin Code</label>
                            <input type="text" class="form-control" name="pin_code" id="edit_pin_code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('editUserForm');
        const updateBaseUrl = @json(url('/ecard/users/my'));

        function setValue(id, value) {
            const el = document.getElementById(id);
            if (el) el.value = value || '';
        }

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btnEditUser');
            if (! btn) return;

            const id = btn.getAttribute('data-id');
            form.action = updateBaseUrl + '/' + encodeURIComponent(id);

            setValue('edit_first_name', btn.getAttribute('data-first_name'));
            setValue('edit_middle_name', btn.getAttribute('data-middle_name'));
            setValue('edit_last_name', btn.getAttribute('data-last_name'));
            setValue('edit_mobile_no', btn.getAttribute('data-mobile_no'));
            setValue('edit_email_id', btn.getAttribute('data-email_id'));
            setValue('edit_business_name', btn.getAttribute('data-business_name'));
            setValue('edit_state', btn.getAttribute('data-state'));
            setValue('edit_district', btn.getAttribute('data-district'));
            setValue('edit_city', btn.getAttribute('data-city'));
            setValue('edit_area', btn.getAttribute('data-area'));
            setValue('edit_pin_code', btn.getAttribute('data-pin_code'));
        });
    })();
</script>
@endsection
