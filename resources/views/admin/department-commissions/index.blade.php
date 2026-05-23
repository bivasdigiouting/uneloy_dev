@extends('layouts.admin')

@section('title', 'Department Level Commission Master')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Department Level Commission Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">System Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Department Module Comm. Master</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <span class="badge bg-info-transparent d-inline-flex align-items-center"><i class="ti ti-info-circle me-1"></i>Update per department row</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Department Commission Settings</h5>
            <span class="text-muted">Edit values and click Update per row</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="department-commissions-table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Department Name</th>
                            <th>Security Amt.</th>
                            <th>Service Charge</th>
                            <th>Admin Charge</th>
                            <th>TDS Charge</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $index => $department)
                            @php
                                $dc = $commissions[$department->id] ?? null;
                            @endphp
                            <tr data-department-id="{{ $department->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $department->department_name }}</td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="security_amount" value="{{ optional($dc)->security_amount }}" placeholder="0.00">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="service_charge" value="{{ optional($dc)->service_charge }}" placeholder="0.00">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="admin_charge" value="{{ optional($dc)->admin_charge }}" placeholder="0.00">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="tds_charge" value="{{ optional($dc)->tds_charge }}" placeholder="0.00">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-update-row"
                                        data-update-url="{{ route('admin.department-commissions.update', $department) }}">
                                        <i class="ti ti-device-floppy"></i> Update
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#department-commissions-table').on('click', '.btn-update-row', function(){
        var btn = $(this);
        var row = btn.closest('tr');
        var departmentId = row.data('department-id');
        var url = btn.data('update-url');

        var payload = {
            _token: '{{ csrf_token() }}',
            security_amount: row.find('input[name="security_amount"]').val(),
            service_charge: row.find('input[name="service_charge"]').val(),
            admin_charge: row.find('input[name="admin_charge"]').val(),
            tds_charge: row.find('input[name="tds_charge"]').val(),
        };

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving');

        $.post(url, payload)
            .done(function(resp){
                if (resp && resp.success) {
                    toastr.success(resp.message || 'Row updated');
                } else {
                    toastr.error((resp && resp.message) || 'Failed to update row');
                }
            })
            .fail(function(xhr){
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var messages = [];
                    Object.values(xhr.responseJSON.errors).forEach(function(arr){
                        messages = messages.concat(arr);
                    });
                    toastr.error(messages.join('\n'));
                } else {
                    toastr.error('Failed to update row');
                }
            })
            .always(function(){
                btn.prop('disabled', false).html('<i class="ti ti-device-floppy"></i> Update');
            });
    });
});
</script>
@endpush
