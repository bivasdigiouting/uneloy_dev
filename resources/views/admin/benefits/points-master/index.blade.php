@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Point Master</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Benefit Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Point Master</li>
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
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Add Point</h5>
                </div>
                <div class="card-body">
                    <form id="pointForm" class="row g-3">
                        <div class="col-12">
                            <label for="type" class="form-label">Select Type</label>
                            <select id="type" name="type" class="form-select">
                                @foreach($types as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="point" class="form-label">Enter Point</label>
                            <input type="number" step="0.01" min="0" id="point" name="point" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="btnAddPoint">Add Point</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-6">
                            <label for="filter_type" class="form-label">Point Type</label>
                            <select id="filter_type" name="filter_type" class="form-select">
                                <option value="">All</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-primary" id="btnSearch">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Point Master List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="pointMasterTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Sl no</th>
                                    <th>Name</th>
                                    <th>Point</th>
                                    <th>Action</th>
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
        var table = $('#pointMasterTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.benefits.points-master.data') }}',
                data: function(d) {
                    d.type = $('#filter_type').val();
                }
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'point', name: 'point' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#btnSearch').on('click', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        $('#pointForm').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                _token: '{{ csrf_token() }}',
                type: $('#type').val(),
                point: $('#point').val()
            };
            $.ajax({
                method: 'POST',
                url: '{{ route('admin.benefits.points-master.store') }}',
                data: formData,
                success: function(resp) {
                    $('#point').val('');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Failed to add point');
                }
            });
        });

        $('#pointMasterTable').on('click', '.delete-point', function() {
            var id = $(this).data('id');
            if (!confirm('Delete this point?')) return;
            $.ajax({
                method: 'POST',
                url: '{{ url('admin/benefits/points-master') }}/' + id,
                data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                success: function(resp) {
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Failed to delete point');
                }
            });
        });
    });
</script>
@endsection