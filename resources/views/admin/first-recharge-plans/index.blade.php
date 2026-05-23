@extends('layouts.admin')

@section('title', 'First Recharge Plan Master')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">First Recharge Plan Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">System Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">First Recharge Plan Master</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.first-recharge-plans.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add Plan
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Plans</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Plan Name</th>
                            <th class="text-end">Plan Value</th>
                            <th class="text-end">Bonus Value</th>
                            <th class="text-end">Total Value</th>
                            <th class="text-end">Benefit Amount</th>
                            <th class="text-end">Benefit Duration (Years)</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 280px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->plan_name }}</td>
                                <td class="text-end">{{ number_format((float) $plan->plan_value, 2) }}</td>
                                <td class="text-end">{{ number_format((float) $plan->bonus_value, 2) }}</td>
                                <td class="text-end">{{ number_format((float) $plan->total_value, 2) }}</td>
                                <td class="text-end">{{ number_format((float) $plan->benefit_amount, 2) }}</td>
                                <td class="text-end">{{ (int) $plan->benefit_duration_years }}</td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning btn-create-commission"
                                        data-plan-id="{{ $plan->id }}"
                                        data-plan-name="{{ $plan->plan_name }}"
                                        data-load-url="{{ route('admin.first-recharge-plans.commissions', $plan->id) }}"
                                        data-save-url="{{ route('admin.first-recharge-plans.commissions.update', $plan->id) }}"
                                    >
                                        <i class="ti ti-settings"></i> Create Commission
                                    </button>
                                    <form action="{{ route('admin.first-recharge-plans.toggle-status', $plan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm ms-1 {{ $plan->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                            {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.first-recharge-plans.edit', $plan->id) }}" class="btn btn-sm btn-info ms-1">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.first-recharge-plans.destroy', $plan->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Delete this plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No plans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<div class="modal fade" id="firstRechargeCommissionModal" tabindex="-1" aria-labelledby="firstRechargeCommissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="firstRechargeCommissionModalLabel">Create Commission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    Set commission amount for each department level.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Department Level</th>
                                <th style="width: 260px;">Commission Amount</th>
                            </tr>
                        </thead>
                        <tbody id="firstRechargeCommissionRows">
                            <tr>
                                <td colspan="2" class="text-center text-muted">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveFirstRechargeCommissions">
                    <i class="ti ti-device-floppy"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        var modalEl = document.getElementById('firstRechargeCommissionModal');
        var modal = new bootstrap.Modal(modalEl);
        var $rows = $('#firstRechargeCommissionRows');
        var $saveBtn = $('#saveFirstRechargeCommissions');
        var currentSaveUrl = null;

        function setLoading() {
            $rows.html('<tr><td colspan="2" class="text-center text-muted">Loading...</td></tr>');
        }

        function setRows(items) {
            if (!items || !items.length) {
                $rows.html('<tr><td colspan="2" class="text-center text-muted">No department levels found.</td></tr>');
                return;
            }

            var html = '';
            items.forEach(function (item) {
                var amount = parseFloat(item.commission_amount);
                if (isNaN(amount)) {
                    amount = 0;
                }
                html += '' +
                    '<tr data-department-id="' + item.department_id + '">' +
                        '<td>' + item.department_name + '</td>' +
                        '<td>' +
                            '<input type="number" step="0.01" min="0" class="form-control form-control-sm commission-amount" value="' + amount.toFixed(2) + '">' +
                        '</td>' +
                    '</tr>';
            });
            $rows.html(html);
        }

        function collectPayload() {
            var commissions = [];
            $rows.find('tr[data-department-id]').each(function () {
                var $tr = $(this);
                commissions.push({
                    department_id: parseInt($tr.data('department-id'), 10),
                    commission_amount: $tr.find('input.commission-amount').val()
                });
            });

            return { commissions: commissions };
        }

        $(document).on('click', '.btn-create-commission', function () {
            var $btn = $(this);
            var planName = $btn.data('plan-name');
            var loadUrl = $btn.data('load-url');
            currentSaveUrl = $btn.data('save-url');

            $('#firstRechargeCommissionModalLabel').text('Create Commission - ' + planName);
            setLoading();
            $saveBtn.prop('disabled', true);
            modal.show();

            $.get(loadUrl)
                .done(function (resp) {
                    if (resp && resp.success) {
                        setRows(resp.rows || []);
                        $saveBtn.prop('disabled', false);
                        return;
                    }
                    toastr.error((resp && resp.message) || 'Failed to load commissions');
                    setRows([]);
                })
                .fail(function () {
                    toastr.error('Failed to load commissions');
                    setRows([]);
                });
        });

        $saveBtn.on('click', function () {
            if (!currentSaveUrl) {
                toastr.error('Save URL missing');
                return;
            }

            var payload = collectPayload();
            $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving');

            $.post(currentSaveUrl, payload)
                .done(function (resp) {
                    if (resp && resp.success) {
                        toastr.success(resp.message || 'Commission saved');
                        modal.hide();
                        return;
                    }
                    toastr.error((resp && resp.message) || 'Failed to save commission');
                })
                .fail(function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var messages = [];
                        Object.values(xhr.responseJSON.errors).forEach(function (arr) {
                            messages = messages.concat(arr);
                        });
                        toastr.error(messages.join('\n'));
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Failed to save commission');
                    }
                })
                .always(function () {
                    $saveBtn.prop('disabled', false).html('<i class="ti ti-device-floppy"></i> Save');
                });
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            currentSaveUrl = null;
            setLoading();
            $saveBtn.prop('disabled', false).html('<i class="ti ti-device-floppy"></i> Save');
        });
    });
</script>
@endpush
