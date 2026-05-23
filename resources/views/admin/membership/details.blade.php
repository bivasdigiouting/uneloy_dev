@extends('layouts.admin')

@section('title', 'My Membership Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">My Membership Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">User Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Membership Details</li>
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

    <!-- Filters + Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end g-3">
                        <div class="col-12 col-md-4">
                            <label for="search_id" class="form-label">Search (ID / User ID / Email / Mobile)</label>
                            <input type="text" class="form-control" id="search_id" placeholder="e.g. 123 or U00123 or user@example.com or 9876543210">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date">
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="plan_type" class="form-label">Plan Type</label>
                            <select id="plan_type" class="form-select">
                                <option value="">All</option>
                                <option value="recharge_1">Recharge 1(s)</option>
                                <option value="recharge_2">Recharge 2(s)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                            <button id="applyFilters" type="button" class="btn btn-primary w-100">Search</button>
                            <button id="resetFilters" type="button" class="btn btn-outline-secondary w-100">Reset</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <div id="exportButtons" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="membershipTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Login ID</th>
                                    <th>Password</th>
                                    <th>MPIN</th>
                                    <th>Security Amount</th>
                                    <th>GST No</th>
                                    <th>UPI Address</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables Responsive & Buttons CSS (CDN) -->
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
    /* Keep profile images tidy */
    .membership-profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }
    #exportButtons .dt-buttons { display: flex; flex-wrap: wrap; gap: .5rem; }
</style>
@endpush

@push('scripts')
<!-- DataTables core is already included in admin footer; add Responsive & Buttons with export deps -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Debounce helper for search
    function debounce(fn, delay) {
        let t; return function() { const ctx = this, args = arguments; clearTimeout(t); t = setTimeout(() => fn.apply(ctx, args), delay); };
    }

    var table = $('#membershipTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: "{{ route('admin.membership.details.data') }}",
            type: 'GET',
            data: function(d) {
                d.search_id = $('#search_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.plan_type = $('#plan_type').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'profile_photo', name: 'profile_photo', orderable: false, searchable: false },
            { data: 'full_name', name: 'full_name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'email_id', name: 'email_id' },
            { data: 'department', name: 'department' },
            { data: 'login_id', name: 'login_id' },
            { data: 'password', name: 'password', orderable: false, searchable: false },
            { data: 'mpin', name: 'mpin', orderable: false, searchable: false },
            { data: 'security_amount', name: 'security_amount', searchable: false },
            { data: 'gst_no', name: 'gst_no' },
            { data: 'upi_address', name: 'upi_address' },
            { data: 'registration_date', name: 'registration_date' }
        ],
        order: [[12, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        buttons: [
            { extend: 'copyHtml5', className: 'btn btn-primary', text: 'Copy' },
            { extend: 'excelHtml5', className: 'btn btn-primary', text: 'Excel' },
            { extend: 'csvHtml5', className: 'btn btn-primary', text: 'CSV' },
            { extend: 'pdfHtml5', className: 'btn btn-primary', text: 'PDF', orientation: 'landscape', pageSize: 'A4' }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching members found",
            emptyTable: "No membership details available"
        }
    });

    // Place export buttons clearly in header
    table.buttons().container().appendTo('#exportButtons');

    // Apply filters via explicit Search button
    $('#applyFilters').on('click', function(){ table.ajax.reload(); });

    // Reload on filter changes & debounced typing
    $('#from_date, #to_date, #plan_type').on('change', function(){ table.ajax.reload(); });
    $('#search_id').on('keyup', debounce(function(){ table.ajax.reload(); }, 400));

    $('#resetFilters').on('click', function() {
        $('#search_id').val('');
        $('#from_date').val('');
        $('#to_date').val('');
        $('#plan_type').val('');
        table.ajax.reload();
    });
});
</script>
@endpush