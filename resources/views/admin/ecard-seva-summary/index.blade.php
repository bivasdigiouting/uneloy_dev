@extends('layouts.admin')

@section('title', 'E-Card Seva Summary')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">E-Card Seva Summary</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">E-Card Seva</li>
                    <li class="breadcrumb-item active" aria-current="page">E-Card Seva Summary</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search</h5>
                </div>
                <div class="card-body">
                    <form id="ecardSevaSummaryFilterForm" class="row g-3">
                        <div class="col-6 col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="state_id" class="form-label">State</label>
                            <select id="state_id" name="state_id" class="form-select">
                                <option value="">All</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="district_id" class="form-label">District</label>
                            <select id="district_id" name="district_id" class="form-select">
                                <option value="">All</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="city_id" class="form-label">City</label>
                            <select id="city_id" name="city_id" class="form-select">
                                <option value="">All</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="q" class="form-label">Search Text</label>
                            <input type="text" id="q" name="q" class="form-control" placeholder="Search by State/District/City">
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-2">
                            <button type="button" id="btnEcardSevaSummarySearch" class="btn btn-primary me-2">
                                <i class="ti ti-search"></i> Search
                            </button>
                            <button type="button" id="btnEcardSevaSummaryReset" class="btn btn-secondary">
                                <i class="ti ti-refresh"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registrations Summary</h5>
                </div>
                <div class="card-body">
                    <table id="ecardSevaSummaryTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>State</th>
                                <th>District</th>
                                <th>City</th>
                                <th>Total Registrations</th>
                                <th>Active</th>
                                <th>Inactive</th>
                                <th>Latest Registration</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Totals:</th>
                                <th id="sumTotal">0</th>
                                <th id="sumActive">0</th>
                                <th id="sumInactive">0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
(function() {
    var table = $('#ecardSevaSummaryTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn btn-secondary' },
            { extend: 'excel', className: 'btn btn-success' },
            { extend: 'pdf', className: 'btn btn-danger' },
            { extend: 'print', className: 'btn btn-info' }
        ],
        ajax: {
            url: '{{ route('admin.ecard-seva-summary.data') }}',
            data: function (d) {
                var formData = $('#ecardSevaSummaryFilterForm').serializeArray();
                formData.forEach(function(item) { d[item.name] = item.value; });
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'state', name: 'state' },
            { data: 'district', name: 'district' },
            { data: 'city', name: 'city' },
            { data: 'total_registrations', name: 'total_registrations', orderable: false, searchable: false },
            { data: 'active_count', name: 'active_count', orderable: false, searchable: false },
            { data: 'inactive_count', name: 'inactive_count', orderable: false, searchable: false },
            { data: 'latest_registration_date', name: 'latest_registration_date', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });

    // Search & Reset
    $('#btnEcardSevaSummarySearch').on('click', function () {
        table.ajax.reload();
    });
    $('#btnEcardSevaSummaryReset').on('click', function () {
        $('#ecardSevaSummaryFilterForm')[0].reset();
        $('#district_id').empty().append('<option value="">All</option>');
        $('#city_id').empty().append('<option value="">All</option>');
        table.ajax.reload();
    });

    // Totals footer update
    $('#ecardSevaSummaryTable').on('xhr.dt', function (e, settings, json, xhr) {
        if (json && json.sum_total !== undefined) {
            $('#sumTotal').text(json.sum_total);
        }
        if (json && json.sum_active !== undefined) {
            $('#sumActive').text(json.sum_active);
        }
        if (json && json.sum_inactive !== undefined) {
            $('#sumInactive').text(json.sum_inactive);
        }
    });

    // Cascading dropdowns for Districts and Cities
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        $('#district_id').empty().append('<option value="">All</option>');
        $('#city_id').empty().append('<option value="">All</option>');
        if (stateId) {
            $.ajax({
                url: '{{ route('admin.user-ecard-report.districts') }}',
                data: { state_id: stateId },
                success: function(res) {
                    if (Array.isArray(res)) {
                        res.forEach(function(d) {
                            $('#district_id').append('<option value="' + d.id + '">' + d.district_name + '</option>');
                        });
                    }
                }
            });
        }
    });

    $('#district_id').on('change', function() {
        var distId = $(this).val();
        $('#city_id').empty().append('<option value="">All</option>');
        if (distId) {
            $.ajax({
                url: '{{ route('admin.user-ecard-report.cities') }}',
                data: { district_id: distId },
                success: function(res) {
                    if (Array.isArray(res)) {
                        res.forEach(function(c) {
                            $('#city_id').append('<option value="' + c.id + '">' + c.city_name + '</option>');
                        });
                    }
                }
            });
        }
    });
})();
</script>
@endpush