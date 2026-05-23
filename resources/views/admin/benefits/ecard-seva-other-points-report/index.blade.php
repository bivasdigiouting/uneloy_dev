@extends('layouts.admin')

@section('title', 'E-Card Seva Other Points Report')

@push('styles')
    {{-- DataTables CSS via CDN (consistent with Blood Donate page approach) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">E-Card Seva Other Points Report</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Benefit Module</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">E-Card Seva Other Points Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Filter</h4>
                        </div>
                        <div class="card-body">
                            <form id="filterForm" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="from_date">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="to_date">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="All">All</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Send Point">Send Point</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Blood Group</label>
                                    <select class="form-select" name="blood_group">
                                        <option value="All">All</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Search by Name/Mobile No.</label>
                                    <input type="text" class="form-control" name="search_text" placeholder="Enter name or mobile number">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="button" id="btnSearch" class="btn btn-primary me-2">Search</button>
                                    <button type="reset" id="btnReset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Report List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="reportTable" class="table table-striped table-bordered nowrap" style="width:100%">
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
                                        <th>Blood Type</th>
                                        <th>Hospital Name</th>
                                        <th>Hospital Address</th>
                                        <th>Req. Date</th>
                                        <th>Image</th>
                                        <th>Status</th>
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- jQuery & Bootstrap (assumed globally loaded in admin layout) --}}
    {{-- DataTables core and extensions via CDN --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
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
            const table = $('#reportTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                ajax: {
                    url: '{{ route('admin.benefits.ecard-seva-other-points-report.data') }}',
                    data: function (d) {
                        const formData = $('#filterForm').serializeArray();
                        formData.forEach(item => { d[item.name] = item.value; });
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
                    { data: 'image_link', name: 'image_link', orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'send_points', name: 'send_points' },
                    { data: 'remarks', name: 'remarks' },
                    { data: 'send_points_date', name: 'send_points_date' },
                ],
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', className: 'btn btn-sm btn-secondary' },
                    { extend: 'csv', className: 'btn btn-sm btn-secondary' },
                    { extend: 'excel', className: 'btn btn-sm btn-secondary' },
                    { extend: 'pdf', className: 'btn btn-sm btn-secondary' },
                ],
            });

            $('#btnSearch').on('click', function () {
                table.ajax.reload();
            });

            $('#btnReset').on('click', function () {
                $('#filterForm')[0].reset();
                table.ajax.reload();
            });
        })();
    </script>
@endpush