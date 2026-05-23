@extends('ecard.ecard')

@section('title', 'Upgrade Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="mb-0">Upgrade Report</h5>
            <small class="text-muted">Track user upgrades with filters</small>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="filtersForm" class="row g-3">
                <div class="col-md-3">
                    <label for="member_no" class="form-label">Member ID</label>
                    <input type="text" id="member_no" name="member_no" class="form-control" placeholder="Enter Member ID">
                </div>
                <div class="col-md-3">
                    <label for="level" class="form-label">Level</label>
                    <select id="level" name="level" class="form-select">
                        <option value="">All</option>
                        @foreach($levels as $lvl)
                            <option value="{{ $lvl }}">{{ ucwords(str_replace('_',' ', $lvl)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From date</label>
                    <input type="date" id="from_date" name="from_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To date</label>
                    <input type="date" id="to_date" name="to_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="search_text" class="form-label">Search</label>
                    <input type="text" id="search_text" name="search_text" class="form-control" placeholder="Name, email, or mobile">
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="button" id="applyFilters" class="btn btn-primary">
                        <i class="fa fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="upgradeTable" class="table table-sm table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Member No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>From Level</th>
                            <th>To Level</th>
                            <th>Upgraded By</th>
                            <th>Remark</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const dataUrl = "{{ route('ecard.upgrade.report.data') }}";

    let dt = null;

    function loadTable() {
        if (dt) {
            dt.destroy();
            document.querySelector('#upgradeTable tbody').innerHTML = '';
        }
        dt = $('#upgradeTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[9, 'desc']],
            language: { emptyTable: 'No upgrades found for selected filters.' },
            ajax: {
                url: dataUrl,
                data: function (d) {
                    d.member_no = $('#member_no').val();
                    d.level = $('#level').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.search_text = $('#search_text').val();
                }
            },
            columns: [
                { data: null, name: 'srno', orderable: false, searchable: false,
                  render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'member_no', name: 'member_no' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'mobile_no', name: 'mobile_no' },
                { data: 'from_level', name: 'from_level' },
                { data: 'to_level', name: 'to_level' },
                { data: 'upgraded_by', name: 'upgraded_by' },
                { data: 'remark', name: 'remark', orderable: false },
                { data: 'created_at', name: 'created_at' },
            ]
        });
    }

    $(document).ready(function () {
        loadTable();
        $('#applyFilters').on('click', function () { loadTable(); });
    });
</script>
@endsection