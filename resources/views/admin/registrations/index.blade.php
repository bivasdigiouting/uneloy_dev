@extends('layouts.admin')

@section('title', 'Registrations')

@section('content')
<div class="content">
        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Registrations</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Registrations</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.registrations.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add Registration
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

        <!-- Registrations List -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h5>Registrations List</h5>
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
                        <table class="table table-striped table-nowrap registrations-datatable mb-0" id="registrations-table">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>User Details</th>
                                    <th>Business Name</th>
                                    <th>Contact Info</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <h6>Are you sure you want to delete this registration?</h6>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script>
    // Initialize DataTable (server-side)
    $(document).ready(function() {
        console.log('Initializing server-side DataTable for registrations...');
        try {
            var table = $('#registrations-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.registrations.index') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_details', name: 'full_name', orderable: false },
                    { data: 'business_name', name: 'business_name' },
                    { data: 'contact_info', name: 'mobile_no', orderable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[5, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "Search registrations:",
                    lengthMenu: "Show _MENU_ registrations per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ registrations",
                    infoEmpty: "No registrations available",
                    emptyTable: "No registrations found",
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                }
            });
            console.log('DataTable initialized successfully:', table);
        } catch (error) {
            console.error('DataTable initialization failed:', error);
        }
    });

    function deleteRegistration(registrationId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `{{ url('admin/registrations') }}/${registrationId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Update status function (fix URL to match routes)
    function updateStatus(id, status) {
        if (confirm('Are you sure you want to ' + status + ' this registration?')) {
            $.ajax({
                url: '{{ url("admin/registrations") }}/' + id + '/update-status',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'status': status
                },
                success: function(response) {
                    // Reload DataTable without full page reload
                    $('#registrations-table').DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                    alert('Error updating status');
                }
            });
        }
    }
</script>

@if(session('user_credentials'))
<script>
    // Display SweetAlert with user credentials
    Swal.fire({
        title: 'Registration Successful!',
        html: `
            <div style="text-align: left; padding: 20px;">
                <h4 style="color: #28a745; margin-bottom: 20px;">✅ User Created Successfully</h4>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
                    <h5 style="color: #495057; margin-bottom: 15px;">📋 User Details:</h5>
                    <p><strong>👤 Full Name:</strong> {{ session('user_credentials.full_name') }}</p>
                    <p><strong>📧 Email:</strong> {{ session('user_credentials.email') }}</p>
                    <p><strong>🏢 Department:</strong> {{ ucwords(str_replace('_', ' ', session('user_credentials.department'))) }} Member</p>
                </div>
                
                <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #007bff;">
                    <h5 style="color: #0056b3; margin-bottom: 15px;">🔐 Login Credentials:</h5>
                    <p><strong>🆔 User ID:</strong> <code style="background: #fff; padding: 2px 6px; border-radius: 3px; color: #d63384;">{{ session('user_credentials.user_id') }}</code></p>
                    <p><strong>🔑 Password:</strong> <code style="background: #fff; padding: 2px 6px; border-radius: 3px; color: #d63384;">{{ session('user_credentials.password') }}</code></p>
                </div>
                
                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107;">
                    <p style="margin: 0; color: #856404;"><strong>📧 Email Sent:</strong> Login credentials have been sent to the user's email address.</p>
                </div>
                
                <div style="background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #17a2b8;">
                    <p style="margin: 0; color: #0c5460;"><strong>🔗 User Login:</strong> The user can now login at <a href="{{ url('/user/login') }}" target="_blank">{{ url('/user/login') }}</a></p>
                </div>
            </div>
        `,
        icon: 'success',
        width: 600,
        showConfirmButton: true,
        confirmButtonText: 'Got it!',
        confirmButtonColor: '#28a745',
        allowOutsideClick: false,
        allowEscapeKey: false
    });
</script>
@endif

@endpush