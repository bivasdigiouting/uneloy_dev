@extends('layouts.admin')

@section('content')
<div class="container">
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Emergency E-Card Seva Other Points Report</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Benefit Module</li>
                <li class="breadcrumb-item active">Emergency E-Card Seva Other Points Report</li>
            </ul>
        </div>
    </div>
    <hr>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Filter</h5>
    </div>
    <div class="card-body">
        <form id="filter-form" class="row g-3">
            <div class="col-md-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" id="from_date" name="from_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" id="to_date" name="to_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search_text" class="form-label">Search (Name/Mobile No.)</label>
                <input type="text" id="search_text" name="search_text" class="form-control" placeholder="Enter name or mobile number">
            </div>
            <div class="col-12">
                <button type="button" id="btn-search" class="btn btn-primary">Search</button>
                <button type="button" id="btn-reset" class="btn btn-secondary ms-2">Reset</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Report List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="report-table" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Action</th>
                        <th>Points</th>
                        <th>Approved (IDNo,Name)</th>
                        <th>Approved Date</th>
                        <th>Name</th>
                        <th>Mobile No.</th>
                        <th>Emergency Type</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Live Location</th>
                        <th>Description</th>
                        <th>Req. Date</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Send Points Remarks</th>
                        <th>Send Points Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(function() {
            var table = $('#report-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf'],
                ajax: {
                    url: '{{ route('admin.benefits.emergency-ecard-seva-other-points-report.data') }}',
                    data: function (d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.status = $('#status').val();
                        d.search_text = $('#search_text').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'points', name: 'points' },
                    { data: 'approved', name: 'approved', orderable: false, searchable: false },
                    { data: 'approved_date', name: 'approved_date' },
                    { data: 'name', name: 'name' },
                    { data: 'mobile_no', name: 'mobile_no' },
                    { data: 'emergency_type', name: 'emergency_type' },
                    { data: 'age', name: 'age' },
                    { data: 'gender', name: 'gender' },
                    { data: 'live_location', name: 'live_location' },
                    { data: 'description', name: 'description' },
                    { data: 'request_date', name: 'request_date' },
                    { data: 'image_link', name: 'image_link', orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'send_points_remarks', name: 'send_points_remarks' },
                    { data: 'send_points_date', name: 'send_points_date' },
                ],
                order: [[12, 'desc']]
            });

            $('#btn-search').on('click', function () {
                table.ajax.reload();
            });
            $('#btn-reset').on('click', function () {
                $('#filter-form')[0].reset();
                table.ajax.reload();
            });
        });
    </script>
@endsection