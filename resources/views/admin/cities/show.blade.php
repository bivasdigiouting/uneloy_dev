@extends('layouts.admin')

@section('title', 'View City')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">City Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cities.index') }}">City Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to City Master
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">City Information</h4>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit City
                                </a>
                                <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- City Information -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Basic Information</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>City Name:</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $city->city_name }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>State:</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $city->state ? $city->state->state_name : 'N/A' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>District:</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $city->district ? $city->district->district_name : 'N/A' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Status:</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        @if($city->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Timestamps</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Created At:</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $city->created_at ? $city->created_at->format('d M Y, h:i A') : 'N/A' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Updated At:</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $city->updated_at ? $city->updated_at->format('d M Y, h:i A') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                                <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit City
                                </a>
                                <button type="button" class="btn btn-warning toggle-status" data-id="{{ $city->id }}">
                                    <i class="ti ti-toggle-left me-1"></i> Toggle Status
                                </button>
                                <button type="button" class="btn btn-danger delete-city" data-id="{{ $city->id }}">
                                    <i class="ti ti-trash me-1"></i> Delete City
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('click', function() {
        var cityId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to toggle the status of this city?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, toggle it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/cities') }}/" + cityId + "/toggle-status",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Delete City
    $('.delete-city').on('click', function() {
        var cityId = $(this).data('id');
        
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
                    url: "{{ url('admin/cities') }}/" + cityId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.href = "{{ route('admin.cities.index') }}";
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush