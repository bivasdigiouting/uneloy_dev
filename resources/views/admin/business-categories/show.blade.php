@extends('layouts.admin')

@section('title', 'View Business Category')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Business Category Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.business-categories.index') }}">Business Categories</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->category_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.business-categories.edit', $category->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit Category
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.business-categories.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Business Categories
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
                            <h4 class="card-title mb-0 d-flex align-items-center">
                                {{ $category->category_name }}
                                <span class="ms-2">{!! $category->status_badge !!}</span>
                            </h4>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.business-categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-edit me-1"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteCategory({{ $category->id }})">
                                    <i class="ti ti-trash me-1"></i> Delete
                                </button>
                                <a href="{{ route('admin.business-categories.index') }}" class="btn btn-secondary btn-sm">
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
                        <!-- Basic Information -->
                        <div class="col-lg-8">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0"><i class="ti ti-info-circle me-2"></i>Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <dt class="text-muted">Category Name:</dt>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="mb-3">{{ $category->category_name }}</dd>
                                        </div>

                                        <div class="col-sm-4">
                                            <dt class="text-muted">Slug:</dt>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="mb-3">
                                                <code>{{ $category->slug }}</code>
                                            </dd>
                                        </div>

                                        <div class="col-sm-4">
                                            <dt class="text-muted">Description:</dt>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="mb-3">
                                                @if($category->description)
                                                    {{ $category->description }}
                                                @else
                                                    <span class="text-muted">No description provided</span>
                                                @endif
                                            </dd>
                                        </div>

                                        <div class="col-sm-4">
                                            <dt class="text-muted">Status:</dt>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="mb-3">{!! $category->status_badge !!}</dd>
                                        </div>

                                        <div class="col-sm-4">
                                            <dt class="text-muted">Sort Order:</dt>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="mb-0">
                                                <span class="badge bg-info">{{ $category->sort_order }}</span>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0"><i class="ti ti-clock me-2"></i>Metadata</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <dt class="text-muted">Created At:</dt>
                                            <dd class="mb-3">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $category->created_at->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    <i class="ti ti-clock me-1"></i>
                                                    {{ $category->created_at->format('h:i A') }}
                                                    ({{ $category->created_at->diffForHumans() }})
                                                </small>
                                            </dd>

                                            <dt class="text-muted">Last Updated:</dt>
                                            <dd class="mb-3">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $category->updated_at->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    <i class="ti ti-clock me-1"></i>
                                                    {{ $category->updated_at->format('h:i A') }}
                                                    ({{ $category->updated_at->diffForHumans() }})
                                                </small>
                                            </dd>

                                            <dt class="text-muted">Category ID:</dt>
                                            <dd class="mb-0">
                                                <code>#{{ $category->id }}</code>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0"><i class="ti ti-bolt me-2"></i>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }}" 
                                                onclick="toggleStatus({{ $category->id }})">
                                            <i class="ti ti-toggle-{{ $category->is_active ? 'left' : 'right' }} me-1"></i>
                                            {{ $category->is_active ? 'Deactivate' : 'Activate' }} Category
                                        </button>
                                        
                                        <a href="{{ route('admin.business-categories.edit', $category->id) }}" class="btn btn-outline-primary">
                                            <i class="ti ti-edit me-1"></i> Edit Category
                                        </a>
                                        
                                        <button type="button" class="btn btn-outline-danger" onclick="deleteCategory({{ $category->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete Category
                                        </button>
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
                <p>Are you sure you want to delete the category <strong>"{{ $category->category_name }}"</strong>?</p>
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
    // Delete category functionality
    let categoryIdToDelete = null;

    window.deleteCategory = function(id) {
        categoryIdToDelete = id;
        $('#deleteModal').modal('show');
    };

    $('#confirmDelete').on('click', function() {
        if (categoryIdToDelete) {
            $.ajax({
                url: "{{ route('admin.business-categories.index') }}/" + categoryIdToDelete,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.success) {
                        // Redirect to index page with success message
                        window.location.href = "{{ route('admin.business-categories.index') }}";
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    let message = 'An error occurred while deleting the category.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert('error', message);
                }
            });
        }
        categoryIdToDelete = null;
    });

    // Toggle status functionality
    window.toggleStatus = function(id) {
        $.ajax({
            url: "{{ route('admin.business-categories.toggle-status', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
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
                let message = 'An error occurred while updating the category status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
            }
        });
    };

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
        
        // Add new alert at the top of the card body
        $('.card-body').first().prepend(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endpush