@extends('layouts.admin')

@section('title', 'Staff Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Staff Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Office Management
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.staff.index') }}">Staff</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Staff Details</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to List">
                <i class="ti ti-arrow-left"></i>
            </a>
            <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Staff">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Staff Details - {{ $staff->staff_name }}</h4>
                </div>
                <div class="card-body">
                    <!-- Personal Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Personal Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    @if($staff->profile_image)
                                        <img src="{{ Storage::url($staff->profile_image) }}" alt="Profile" class="img-thumbnail rounded-circle" width="150" height="150">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                            <i class="fas fa-user fa-4x text-white"></i>
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        <span class="badge {{ $staff->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                                            {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Staff Name:</label>
                                            <p class="form-control-plaintext">{{ $staff->staff_name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Gender:</label>
                                            <p class="form-control-plaintext">{{ $staff->gender }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Date of Joining:</label>
                                            <p class="form-control-plaintext">{{ date('d M Y', strtotime($staff->date_of_joining)) }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Date of Birth:</label>
                                            <p class="form-control-plaintext">{{ date('d M Y', strtotime($staff->date_of_birth)) }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Designation:</label>
                                            <p class="form-control-plaintext">{{ $staff->designation->designation_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Age:</label>
                                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($staff->date_of_birth)->age }} years</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-address-book"></i> Contact Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Address 1:</label>
                                    <p class="form-control-plaintext">{{ $staff->address_1 }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Address 2:</label>
                                    <p class="form-control-plaintext">{{ $staff->address_2 ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">State:</label>
                                    <p class="form-control-plaintext">{{ $staff->state }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">District:</label>
                                    <p class="form-control-plaintext">{{ $staff->district }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">City:</label>
                                    <p class="form-control-plaintext">{{ $staff->city }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Pincode:</label>
                                    <p class="form-control-plaintext">{{ $staff->pincode }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Mobile No:</label>
                                    <p class="form-control-plaintext">
                                        <a href="tel:{{ $staff->mobile_no }}" class="text-decoration-none">
                                            <i class="fas fa-phone"></i> {{ $staff->mobile_no }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email ID:</label>
                                    <p class="form-control-plaintext">
                                        <a href="mailto:{{ $staff->email_id }}" class="text-decoration-none">
                                            <i class="fas fa-envelope"></i> {{ $staff->email_id }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Location:</label>
                                    <p class="form-control-plaintext">{{ $staff->location ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Full Address:</label>
                                    <p class="form-control-plaintext">{{ $staff->full_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-university"></i> Bank Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">IFSC Code:</label>
                                    <p class="form-control-plaintext">{{ $staff->ifsc_code }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bank Name:</label>
                                    <p class="form-control-plaintext">{{ $staff->bank_name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Branch Name:</label>
                                    <p class="form-control-plaintext">{{ $staff->branch_name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Account No:</label>
                                    <p class="form-control-plaintext">{{ $staff->account_no }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">PAN No:</label>
                                    <p class="form-control-plaintext">{{ $staff->pan_no }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Aadhar No:</label>
                                    <p class="form-control-plaintext">{{ $staff->aadhar_no }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Salary:</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-primary fs-6">₹ {{ number_format($staff->salary, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Login Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-key"></i> Login Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">User ID:</label>
                                    <p class="form-control-plaintext">{{ $staff->user_id }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Account Status:</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $staff->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                                            {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Created At:</label>
                                    <p class="form-control-plaintext">{{ $staff->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Last Updated:</label>
                                    <p class="form-control-plaintext">{{ $staff->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-warning toggle-status" data-url="{{ route('admin.staff.toggle-status', $staff->id) }}">
                            <i class="fas fa-toggle-on"></i> Toggle Status
                        </button>
                        <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Staff
                        </a>
                        <button type="button" class="btn btn-danger delete-staff" data-url="{{ route('admin.staff.destroy', $staff->id) }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('click', function() {
        var url = $(this).data('url');
        var button = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to change the status of this staff member?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });

    // Delete Staff
    $('.delete-staff').on('click', function() {
        var url = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => {
                                window.location.href = '{{ route("admin.staff.index") }}';
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush