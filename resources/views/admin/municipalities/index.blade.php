@extends('layouts.admin')

@section('title', 'Municipality Master')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Municipality Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Master Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">Municipality Master</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="window.location.reload();"><i class="ti ti-refresh-dot"></i></a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col"><h4 class="card-title mb-0">Municipalities List</h4></div>
                        <div class="col-auto"><a href="{{ route('admin.municipalities.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Add New Municipality</a></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="municipalitiesTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Municipality</th>
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
$(function(){
    var table = $('#municipalitiesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.municipalities.index') }}", type: 'GET' },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'municipality_name', name: 'municipality_name' },
            { data: 'state_name', name: 'state.state_name' },
            { data: 'district_name', name: 'district.district_name' },
            { data: 'city_name', name: 'city.city_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1,'asc']],
        responsive: true
    });

    $(document).on('click','.toggle-status',function(){
        var id = $(this).data('id');
        Swal.fire({ title:'Are you sure?', icon:'warning', showCancelButton:true }).then((r)=>{
            if(r.isConfirmed){
                $.post("{{ url('admin/municipalities') }}/"+id+"/toggle-status", { _token: '{{ csrf_token() }}' })
                .done(function(res){ Swal.fire('Updated', res.message, 'success'); table.ajax.reload(); })
                .fail(function(){ Swal.fire('Error','Operation failed','error'); });
            }
        });
    });

    $(document).on('click','.delete-row',function(){
        var id = $(this).data('id');
        Swal.fire({ title:'Delete?', text:'This cannot be undone', icon:'warning', showCancelButton:true }).then((r)=>{
            if(r.isConfirmed){
                $.ajax({ url: "{{ url('admin/municipalities') }}/"+id, type:'DELETE', data:{ _token: '{{ csrf_token() }}' } })
                .done(function(res){ Swal.fire('Deleted', res.message, 'success'); table.ajax.reload(); })
                .fail(function(){ Swal.fire('Error','Delete failed','error'); });
            }
        });
    });
});
</script>
@endpush
