@extends('layouts.admin')

@section('title', 'Village/Town Master')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Village/Town Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Master Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Village/Town Master</li>
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
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Villages/Towns List</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.villages.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add New Village/Town
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="villagesTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Village/Town Name</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function() {
    var table = $('#villagesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.villages.index') }}", type: 'GET' },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'village_name', name: 'village_name' },
            { data: 'state_name', name: 'state.state_name' },
            { data: 'district_name', name: 'district.district_name' },
            { data: 'city_name', name: 'city.city_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            emptyTable: "No villages/towns found",
            zeroRecords: "No matching villages/towns found"
        }
    });

    $(document).on('click', '.toggle-status', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'Toggle status for this village/town?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, toggle it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/villages') }}/" + id + "/toggle-status",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.fire(res.success ? 'Updated!' : 'Error!', res.message, res.success ? 'success' : 'error');
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                })
            }
        });
    });

    $(document).on('click', '.delete-village', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/villages') }}/" + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.fire(res.success ? 'Deleted!' : 'Error!', res.message, res.success ? 'success' : 'error');
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                })
            }
        });
    });
});
</script>
@endpush

