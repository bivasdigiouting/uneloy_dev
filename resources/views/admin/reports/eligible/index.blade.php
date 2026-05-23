@extends('layouts.admin')

@section('title', 'Eligible Report')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Eligible Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Benefit Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Eligible Report</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2"></div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Filters</h4></div>
                <div class="card-body">
                    <form id="eligibleFilterForm" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Select Scheme</label>
                            <select name="scheme" id="scheme" class="form-select">
                                @foreach($schemes as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Select Scheme Type</label>
                            <select name="scheme_type" id="scheme_type" class="form-select">
                                @foreach($schemeTypes as $item)
                                    <option value="{{ $item }}">{{ ucfirst($item) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Search By</label>
                            <input type="text" name="search_text" id="search_text" class="form-control" placeholder="Enter keyword" />
                        </div>
                        <div class="col-12">
                            <button type="button" id="searchBtn" class="btn btn-primary me-2"><i class="ti ti-search"></i> Search</button>
                            <button type="button" id="resetBtn" class="btn btn-secondary"><i class="ti ti-refresh"></i> Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Report List</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="eligibleTable" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Scheme Name</th>
                                    <th>Scheme Type</th>
                                    <th>User ID No</th>
                                    <th>User Name</th>
                                    <th>Eligible Date</th>
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
@endsection

@section('scripts')
<script>
    $(function() {
        var table = $('#eligibleTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', className: 'btn btn-secondary' },
                { extend: 'csv', className: 'btn btn-secondary' },
                { extend: 'excel', className: 'btn btn-secondary' },
                { extend: 'pdf', className: 'btn btn-secondary' }
            ],
            ajax: {
                url: "{{ route('admin.reports.eligible.data') }}",
                data: function (d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.scheme = $('#scheme').val();
                    d.scheme_type = $('#scheme_type').val();
                    d.search_text = $('#search_text').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'scheme_name', name: 'scheme_name' },
                { data: 'scheme_type', name: 'scheme_type' },
                { data: 'user_id_no', name: 'user_id_no' },
                { data: 'user_name', name: 'user_name' },
                { data: 'eligible_date', name: 'eligible_date' },
            ]
        });

        $('#searchBtn').on('click', function() {
            table.ajax.reload();
        });
        $('#resetBtn').on('click', function() {
            $('#eligibleFilterForm')[0].reset();
            table.ajax.reload();
        });
    });
</script>
@endsection