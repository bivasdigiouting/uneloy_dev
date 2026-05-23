@extends('layouts.admin')

@section('title', 'Our Team')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Our Team</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Website Modules</li>
                    <li class="breadcrumb-item">About Us</li>
                    <li class="breadcrumb-item active" aria-current="page">Our Team</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="mb-2">
                <a href="{{ route('admin.our-team.create') }}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-plus me-1"></i>Add New Member</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped custom-table" id="teamTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Contact No</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teamMembers as $index => $member)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($member->image)
                                            <img src="{{ $member->image_url }}" alt="{{ $member->name }}" width="50" class="rounded">
                                        @else
                                            <span class="badge bg-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->designation }}</td>
                                    <td>{{ $member->contact_no ?? 'N/A' }}</td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input toggle-status" type="checkbox" role="switch" data-id="{{ $member->id }}" {{ $member->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center fs-15">
                                            <a href="{{ route('admin.our-team.edit', $member->id) }}" class="btn btn-sm btn-info text-white me-2" title="Edit"><i class="ti ti-edit"></i></a>
                                            <form action="{{ route('admin.our-team.destroy', $member->id) }}" method="POST" class="delete-form d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger text-white delete-btn" title="Delete"><i class="ti ti-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#teamTable').DataTable();

    $('.toggle-status').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        
        $.ajax({
            type: "PATCH",
            dataType: "json",
            url: "{{ route('admin.our-team.toggle-status', '') }}/" + id,
            data: {'status': status, '_token': '{{ csrf_token() }}'},
            success: function(data){
                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Status updated successfully!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            },
            error: function(){
                Swal.fire('Error', 'Something went wrong!', 'error');
            }
        });
    });

    $('.delete-btn').click(function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
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
                form.submit();
            }
        });
    });
});
</script>
@endpush
