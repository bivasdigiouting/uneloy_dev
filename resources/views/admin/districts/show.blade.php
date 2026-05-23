@extends('layouts.admin')

@section('title', 'District Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">District Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.districts.index') }}">District Master</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $district->district_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.districts.edit', $district->id) }}" class="btn btn-warning d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit District
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.districts.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to District Master
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
                            <h4 class="card-title mb-0">{{ $district->district_name }}</h4>
                            <p class="text-muted mb-0">District Information</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.districts.edit', $district->id) }}" class="btn btn-warning btn-sm">
                                    <i class="ti ti-edit me-1"></i> Edit
                                </a>
                                <a href="{{ route('admin.districts.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- District Information -->
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold" style="width: 200px;">District Name:</td>
                                            <td>{{ $district->district_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">State Name:</td>
                                            <td>
                                                <a href="{{ route('admin.states.show', $district->state->id) }}" class="text-primary text-decoration-none">
                                                    {{ $district->state->state_name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Status:</td>
                                            <td>{!! $district->status_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Created At:</td>
                                            <td>{{ $district->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Last Updated:</td>
                                            <td>{{ $district->updated_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        @if($district->created_at != $district->updated_at)
                                        <tr>
                                            <td class="fw-semibold">Last Modified:</td>
                                            <td>{{ $district->updated_at->diffForHumans() }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Action Panel -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.districts.edit', $district->id) }}" class="btn btn-warning btn-sm">
                                            <i class="ti ti-edit me-1"></i> Edit District
                                        </a>
                                        
                                        @if($district->status == 'active')
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="toggleStatus({{ $district->id }}, 'inactive')">
                                                <i class="ti ti-toggle-left me-1"></i> Deactivate
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="toggleStatus({{ $district->id }}, 'active')">
                                                <i class="ti ti-toggle-right me-1"></i> Activate
                                            </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteDistrict({{ $district->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete District
                                        </button>
                                        
                                        <a href="{{ route('admin.districts.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="ti ti-arrow-left me-1"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- State Information Card -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">State Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h6 class="mb-1">{{ $district->state->state_name }}</h6>
                                        <p class="text-muted mb-2">{!! $district->state->status_badge !!}</p>
                                        <a href="{{ route('admin.states.show', $district->state->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-eye me-1"></i> View State Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the district <strong>"{{ $district->district_name }}"</strong>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});

// Delete district functionality
let districtIdToDelete = null;

function deleteDistrict(id) {
    districtIdToDelete = id;
    $('#deleteModal').modal('show');
}

$('#confirmDelete').on('click', function() {
    if (districtIdToDelete) {
        $.ajax({
            url: "{{ route('admin.districts.index') }}/" + districtIdToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    // Redirect to districts index with success message
                    window.location.href = "{{ route('admin.districts.index') }}?deleted=1";
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');
                let message = 'An error occurred while deleting the district.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
            }
        });
    }
    districtIdToDelete = null;
});

// Toggle status functionality
function toggleStatus(id, newStatus) {
    const actionText = newStatus === 'active' ? 'activate' : 'deactivate';
    
    if (confirm(`Are you sure you want to ${actionText} this district?`)) {
        $.ajax({
            url: "{{ route('admin.districts.index') }}/" + id + "/toggle-status",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    // Reload the page to show updated status
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while updating the district status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
            }
        });
    }
}

// Alert function
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the card
    $('.card-body').prepend(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush