@extends('layouts.admin')

@section('title', 'Camp Summary Report')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Camp Summary Report</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Benefit Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Camp Summary Report</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" id="from_date" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" id="to_date" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label for="camp_id" class="form-label">Select Camp</label>
                    <select id="camp_id" class="form-select">
                        <option value="">All Camps</option>
                        @foreach($camps as $camp)
                            <option value="{{ $camp->id }}">{{ $camp->camp_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="city_id" class="form-label">Select City</label>
                    <select id="city_id" class="form-select" disabled>
                        <option value="">All Cities</option>
                    </select>
                </div>
                <div class="col-12">
                    <button id="searchBtn" class="btn btn-primary"><i class="ti ti-search"></i> Search</button>
                    <button id="resetBtn" class="btn btn-light ms-2"><i class="ti ti-rotate"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Filters -->

    <!-- Report Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="campSummaryTable" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Camp Name</th>
                            <th>City Name</th>
                            <th>Title</th>
                            <th>Capacity</th>
                            <th>Total participant user</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Banner</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /Report Table -->
</div>
@endsection

@push('scripts')
<!-- DataTables Buttons and dependencies -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        const citiesUrl = "{{ route('admin.reports.camp-summary.cities') }}";
        const indexUrl = "{{ route('admin.reports.camp-summary.index') }}";

        function loadCities(campId) {
            $('#city_id').prop('disabled', true).html('<option value="">All Cities</option>');
            if (!campId) return;
            $.get(citiesUrl, { camp_id: campId })
                .done(function(res) {
                    const cities = (res && res.data) ? res.data : [];
                    let opts = '<option value="">All Cities</option>';
                    cities.forEach(c => { opts += `<option value="${c.id}">${c.city_name}</option>`; });
                    $('#city_id').html(opts).prop('disabled', false);
                });
        }

        $('#camp_id').on('change', function() {
            loadCities($(this).val());
        });

        const table = $('#campSummaryTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: indexUrl,
                type: 'GET',
                data: function(d) {
                    d.camp_id = $('#camp_id').val();
                    d.city_id = $('#city_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'camp_name', name: 'camp.camp_name' },
                { data: 'city_name', name: 'city.city_name' },
                { data: 'title', name: 'title' },
                { data: 'capacity', name: 'capacity' },
                { data: 'total_participants', name: 'total_participants', orderable: false, searchable: false },
                { data: 'from_date', name: 'from_date' },
                { data: 'to_date', name: 'to_date' },
                { data: 'banner', name: 'banner', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            pageLength: 25,
            dom: '<"row mb-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"B>>frtip',
            buttons: [
                { extend: 'copy', className: 'btn btn-light btn-sm' },
                { extend: 'csv', className: 'btn btn-light btn-sm' },
                { extend: 'excel', className: 'btn btn-light btn-sm' },
                { extend: 'pdf', className: 'btn btn-light btn-sm' }
            ],
            language: {
                emptyTable: 'No camp summary found',
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });

        $('#searchBtn').on('click', function() {
            table.draw();
        });
        $('#resetBtn').on('click', function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#camp_id').val('');
            $('#city_id').html('<option value="">All Cities</option>').prop('disabled', true);
            table.draw();
        });
    });
</script>
@endpush