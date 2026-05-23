@extends('layouts.admin')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Blood Donate Other Points Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Benefit Module</li>
                        <li class="breadcrumb-item active">Blood Donate Other Points Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filters</h5>
            </div>
            <div class="card-body">
                <form id="bdopr-filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="All">All</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Blood Group</label>
                        <select class="form-select" id="blood_group" name="blood_group">
                            <option value="All">All</option>
                            @foreach($bloodGroups as $bg)
                                <option value="{{ $bg }}">{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Search (Name/Mobile No.)</label>
                        <input type="text" class="form-control" id="search_text" name="search_text" placeholder="Enter name or mobile number">
                    </div>
                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                        <button type="button" id="btn-search" class="btn btn-primary me-2">Search</button>
                        <button type="button" id="btn-reset" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report List Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Report List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bdopr-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Action</th>
                                <th>Points</th>
                                <th>Approved (IDNo,Name)</th>
                                <th>Approved Date</th>
                                <th>Name</th>
                                <th>Mobile No.</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Blood Group</th>
                                <th>Hospital Name</th>
                                <th>Hospital Address</th>
                                <th>Req. Date</th>
                                <th>Status</th>
                                <th>Proof Document</th>
                                <th>Upload Proof Document</th>
                                <th>Proof Remarks</th>
                                <th>Send Points</th>
                                <th>Remarks</th>
                                <th>Send Points Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<!-- DataTables CSS (core loaded in layout); add Responsive + Buttons via CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endpush

@push('scripts')
<!-- DataTables JS via CDN to avoid missing local assets -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<!-- Export dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-1mB8qsoY9S9QqB7F7SlFhG8nT+f4sYbqpYHu5xMDLwMtI0vD5ObJYFSjUnDgItkjaawYtMPfYMiC3kVBSnCqJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-+vR7mXf8E8K9pVwB0Wqg4q3QmHnN8FhPlj3i6lE8b8pQYIfJ6S2lHcHcRQg3nYeo2iVYv+3i9wC1tQm2zZae6Lg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha512-S8u6FJzKqHq7ZQY8rTmTIRJ6Qb1wKfKubGVy2tP3VOk1Z2hC7mHjzqZ7n3Rkq3kRDYvO5X+1EQvHcLa2H63cRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#bdopr-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.benefits.blood-donate-other-points-report.data') }}",
            data: function (d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.status = $('#status').val();
                d.blood_group = $('#blood_group').val();
                d.search_text = $('#search_text').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'points', name: 'points' },
            { data: 'approved', name: 'approved', orderable: false },
            { data: 'approved_date', name: 'approved_date' },
            { data: 'name', name: 'name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'age', name: 'age' },
            { data: 'gender', name: 'gender' },
            { data: 'blood_group', name: 'blood_group' },
            { data: 'hospital_name', name: 'hospital_name' },
            { data: 'hospital_address', name: 'hospital_address' },
            { data: 'request_date', name: 'request_date' },
            { data: 'status', name: 'status' },
            { data: 'proof_document_link', name: 'proof_document_link', orderable: false, searchable: false },
            { data: 'upload_proof_document_link', name: 'upload_proof_document_link', orderable: false, searchable: false },
            { data: 'proof_remarks', name: 'proof_remarks' },
            { data: 'send_points', name: 'send_points' },
            { data: 'send_points_remarks', name: 'send_points_remarks' },
            { data: 'send_points_date', name: 'send_points_date' }
        ],
        order: [[12, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn btn-secondary' },
            { extend: 'csv', className: 'btn btn-info' },
            { extend: 'excel', className: 'btn btn-success' },
            { extend: 'pdf', className: 'btn btn-danger' }
        ]
    });

    $('#btn-search').on('click', function() {
        table.ajax.reload();
    });

    $('#btn-reset').on('click', function() {
        $('#bdopr-filter-form')[0].reset();
        table.ajax.reload();
    });
});
</script>
@endpush