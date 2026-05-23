@extends('layouts.admin')

@section('title', 'E-Card Registrations')

@section('content')
<div class="content">
        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">E-Card Registrations</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">E-Card Seva</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Registration</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.ecard-registrations.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add E-Card Registration
                </a>
            </div>
        </div>
        </div>
        <!-- /Breadcrumb -->

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- E-Card Registrations List -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h5>E-Card Registrations List</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-white border btn-sm d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                <i class="ti ti-file-export me-1"></i>Export
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-3">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">
                                        <i class="ti ti-file-type-pdf me-1"></i>Export as PDF
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">
                                        <i class="ti ti-file-type-xls me-1"></i>Export as Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-nowrap ecard-registrations-datatable mb-0" id="ecard-registrations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Details</th>
                                    <th>Business Name</th>
                                    <th>Department Level</th>
                                    <th>Contact Info</th>
                                    <th>Status</th>
                                    <th>KYC Status</th>
                                    <th>Wallet Balance</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $registration)
                                <tr>
                                    <td>
                                        <span class="fw-medium">#{{ $registration->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('admin.ecard-registrations.show', $registration->id) }}" class="avatar avatar-md me-2">
                                                <img src="{{ asset('backend-assets/img/profiles/avatar-01.jpg') }}" class="img-fluid rounded-circle" alt="{{ $registration->full_name }}">
                                            </a>
                                            <div>
                                                <h6 class="fw-medium">
                                                    <a href="{{ route('admin.ecard-registrations.show', $registration->id) }}">{{ $registration->full_name }}</a>
                                                </h6>
                                                <span class="fs-12 text-muted">{{ $registration->email_id ?? 'No email' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">
                                            {{ $registration->business_name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">
                                            {{ $registration->department_level ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium d-block">{{ $registration->mobile_no ?? 'No mobile' }}</span>
                                            <span class="fs-12 text-muted">{{ $registration->email_id ?? 'No email' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($registration->status == 'pending')
                                            <span class="badge bg-warning-transparent"><i class="ti ti-clock me-1"></i>Pending</span>
                                        @elseif($registration->status == 'active')
                                            <span class="badge bg-success-transparent"><i class="ti ti-check me-1"></i>Active</span>
                                        @elseif($registration->status == 'inactive')
                                            <span class="badge bg-secondary-transparent"><i class="ti ti-pause me-1"></i>Inactive</span>
                                        @elseif($registration->status == 'rejected')
                                            <span class="badge bg-danger-transparent"><i class="ti ti-x me-1"></i>Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($registration->kyc_status == 'pending')
                                            <span class="badge bg-warning-transparent"><i class="ti ti-clock me-1"></i>Pending</span>
                                        @elseif($registration->kyc_status == 'approved')
                                            <span class="badge bg-success-transparent"><i class="ti ti-check me-1"></i>Approved</span>
                                        @elseif($registration->kyc_status == 'rejected')
                                            <span class="badge bg-danger-transparent"><i class="ti ti-x me-1"></i>Rejected</span>
                                        @else
                                            <span class="badge bg-secondary-transparent"><i class="ti ti-minus me-1"></i>N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-medium text-success">₹{{ number_format($registration->wallet_balance, 2) }}</span>
                                    </td>
                                    <td>{{ $registration->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <a href="javascript:void(0);" class="btn btn-white btn-icon btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end p-3">
                                                <li>
                                                    <a class="dropdown-item rounded-1" href="{{ route('admin.ecard-registrations.show', $registration->id) }}">
                                                        <i class="ti ti-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item rounded-1" href="{{ route('admin.ecard-registrations.edit', $registration->id) }}">
                                                        <i class="ti ti-edit me-2"></i>Edit Registration
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item rounded-1" href="{{ route('admin.ecard-permissions.user', $registration->id) }}">
                                                        <i class="ti ti-shield-check me-2"></i>Set User Permissions
                                                    </a>
                                                </li>
                                                @if($registration->status == 'pending')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item rounded-1 text-success" href="javascript:void(0);" onclick="updateStatus({{ $registration->id }}, 'active')">
                                                        <i class="ti ti-check me-2"></i>Activate
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item rounded-1 text-warning" href="javascript:void(0);" onclick="updateStatus({{ $registration->id }}, 'rejected')">
                                                        <i class="ti ti-x me-2"></i>Reject
                                                    </a>
                                                </li>
                                                @elseif($registration->status == 'active')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item rounded-1 text-warning" href="javascript:void(0);" onclick="updateStatus({{ $registration->id }}, 'inactive')">
                                                        <i class="ti ti-pause me-2"></i>Deactivate
                                                    </a>
                                                </li>
                                                @elseif($registration->status == 'inactive')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item rounded-1 text-success" href="javascript:void(0);" onclick="updateStatus({{ $registration->id }}, 'active')">
                                                        <i class="ti ti-check me-2"></i>Activate
                                                    </a>
                                                </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item rounded-1 text-danger" href="javascript:void(0);" onclick="deleteRegistration({{ $registration->id }})">
                                                        <i class="ti ti-trash me-2"></i>Delete Registration
                                                    </a>
                                                </li>
                                            </ul>
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

    @if($registrations->count() === 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ti ti-id-off fs-48 text-muted mb-2"></i>
                        <h6 class="text-muted">No E-Card registrations found</h6>
                        <p class="text-muted mb-3">No E-Card registrations available at the moment</p>
                        <a href="{{ route('admin.ecard-registrations.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i>Add New E-Card Registration
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="ti ti-alert-triangle fs-48 text-warning mb-3"></i>
                    <h6>Are you sure you want to delete this E-Card registration?</h6>
                    <p class="text-muted">This action cannot be undone. All registration data will be permanently removed.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Registration</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
{{-- Responsive plugin removed to avoid conflicts; using base DataTables only --}}
<script>
    // Initialize DataTable
    $(document).ready(function() {
        console.log('Initializing DataTable for E-Card registrations...');
        
        try {
            // Only initialize DataTable if there is at least one data row
            @if($registrations->count() > 0)
                var table = $('#ecard-registrations-table').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    order: [[1, 'asc']],
                    columnDefs: [
                    { orderable: false, targets: [8] } // Disable sorting for Actions column
                    ],
                    language: {
                        search: "Search E-Card registrations:",
                        lengthMenu: "Show _MENU_ E-Card registrations per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ E-Card registrations",
                    infoEmpty: "No E-Card registrations available",
                    emptyTable: "No E-Card registrations found"
                }
            });
            console.log('DataTable initialized successfully:', table);
            @else
            console.log('Skipping DataTable init: no registrations present.');
            @endif
        } catch (error) {
            console.error('DataTable initialization failed:', error);
        }
    });

    // Delete registration function
    function deleteRegistration(registrationId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `{{ url('admin/ecard-registrations') }}/${registrationId}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Update status function
    function updateStatus(id, status) {
        if (confirm('Are you sure you want to change the status to ' + status + '?')) {
            $.ajax({
                url: '{{ url("admin/ecard-registrations") }}/' + id + '/update-status',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'status': status
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error updating status');
                }
            });
        }
    }
</script>
@endpush