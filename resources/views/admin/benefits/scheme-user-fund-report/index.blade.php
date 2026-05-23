@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Scheme User Fund Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Benefit Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Scheme User Fund Report</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form id="reportFilterForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="scheme" class="form-label">Select Scheme</label>
                            <select id="scheme" name="scheme" class="form-select">
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme }}">{{ $scheme }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="search_field" class="form-label">Search By</label>
                            <select id="search_field" name="search_field" class="form-select">
                                <option value="">All</option>
                                <option value="user_id">User Id</option>
                                <option value="user_name">Name</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search_value" class="form-label">Search Value</label>
                            <input type="text" id="search_value" name="search_value" class="form-control" placeholder="Type to search...">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="applyFilterBtn">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Scheme User Fund Report List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="schemeUserFundReportTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>sl no</th>
                                    <th>user id</th>
                                    <th>user name</th>
                                    <th>Date</th>
                                    <th>Distribute fund</th>
                                    <th>Total distribute fund</th>
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
        var table = $('#schemeUserFundReportTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.benefits.scheme-user-fund-report.data') }}',
                data: function(d) {
                    d.scheme = $('#scheme').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.search_field = $('#search_field').val();
                    d.search_value = $('#search_value').val();
                }
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_id', name: 'user_id' },
                { data: 'user_name', name: 'user_name' },
                { data: 'date', name: 'date' },
                { data: 'distribute_fund', name: 'distribute_fund' },
                { data: 'total_distribute_fund', name: 'total_distribute_fund' },
            ]
        });

        $('#applyFilterBtn').on('click', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });
</script>
@endsection